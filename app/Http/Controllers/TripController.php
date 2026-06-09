<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TripController extends Controller
{
    public function index()
    {
        $allTrips = Auth::user()->trips()->with('members')->orderByDesc('created_at')->get();

        // Trip dengan tanggal = trip aktif; tanpa tanggal = wishlist
        $trips     = $allTrips->whereNotNull('start_date')->values();
        $wishlists = $allTrips->whereNull('start_date')->values();

        return view('trips.index', compact('trips', 'wishlists'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $isWishlist = empty($request->start_date) && empty($request->end_date);

        $request->validate([
            'title'        => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'start_date'   => $isWishlist ? 'nullable' : 'required|date',
            'end_date'     => $isWishlist ? 'nullable' : 'required|date|after_or_equal:start_date',
            'total_budget' => 'nullable|numeric|min:0',
        ]);

        $trip = Trip::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'destination' => $request->destination,
            'start_date'  => $request->start_date ?: null,
            'end_date'    => $request->end_date ?: null,
            'total_budget'=> $request->total_budget ?? 0,
            'invite_code' => strtoupper(Str::random(6)),
            'status'      => 'planning',
        ]);

        $trip->members()->attach(Auth::id(), [
            'role'      => 'owner',
            'joined_at' => now(),
        ]);

        // Hanya generate hari-hari jika tanggal diisi
        if (!$isWishlist) {
            $start     = Carbon::parse($request->start_date);
            $end       = Carbon::parse($request->end_date);
            $daysCount = $start->diffInDays($end) + 1;

            for ($i = 0; $i < $daysCount; $i++) {
                TripDay::create([
                    'trip_id'    => $trip->id,
                    'date'       => $start->copy()->addDays($i)->format('Y-m-d'),
                    'day_number' => $i + 1,
                ]);
            }

            return redirect()->route('trips.show', $trip)
                ->with('success', 'Trip berhasil dibuat! Ayo susun timeline. 🚀');
        }

        return redirect()->route('trips.index')
            ->with('success', 'Wishlist trip berhasil disimpan! Isi tanggal kalau udah fix. 💖');
    }

    public function show(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        $trip->load(['days.activities' => function($q) {
            $q->orderBy('sort_order');
        }, 'members']);

        return view('trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        return view('trips.edit', compact('trip'));
    }

    public function update(Request $request, Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'total_budget' => 'nullable|numeric|min:0',
        ]);

        $trip->update($request->only('title', 'description', 'destination', 'total_budget'));

        return redirect()->route('trips.show', $trip)->with('success', 'Trip berhasil diupdate!');
    }

    public function destroy(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);
        $trip->delete();
        return redirect()->route('trips.index')->with('success', 'Trip dihapus.');
    }

    public function summary(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);
        
        $trip->load(['days.activities', 'members', 'expenses']);
        
        // Statistics
        $totalActivities = $trip->days->flatMap->activities->count();
        $completedActivities = $trip->days->flatMap->activities->where('is_completed', true)->count();
        
        $totalBudget = $trip->total_budget;
        $totalSpent = $trip->expenses->sum('amount');
        $remainingBudget = max(0, $totalBudget - $totalSpent);

        $activitiesWithPhotos = $trip->days->flatMap->activities->whereNotNull('photo');

        return view('trips.summary', compact(
            'trip', 
            'totalActivities', 
            'completedActivities', 
            'totalBudget', 
            'totalSpent', 
            'remainingBudget',
            'activitiesWithPhotos'
        ));
    }

    public function complete(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        if ($trip->status === 'completed') {
            return back()->with('info', 'Trip sudah ditandai selesai.');
        }

        $trip->update(['status' => 'completed']);

        return back()->with('success', 'Perjalanan berhasil diselesaikan! Budget tracker akan menampilkan trip ini.');
    }

    public function toggleLike(Trip $trip)
    {
        $user = Auth::user();
        $like = $trip->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $trip->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'count' => $trip->likes()->count(),
            ]);
        }

        return back();
    }

    public function publicShow(Trip $trip)
    {
        if (!$trip->is_public) abort(404);

        $trip->load([
            'days.activities' => fn ($q) => $q->orderBy('sort_order'),
            'members',
            'creator',
            'documents.user',
        ]);

        $totalEstimatedBudget = $trip->days->flatMap->activities->sum('estimated_cost');
        $likeCount = $trip->likes()->count();
        $isLiked = Auth::check() ? $trip->isLikedBy(Auth::user()) : false;
        $alreadyCloned = Auth::check()
            ? Auth::user()->trips()->where('description', 'LIKE', '%[Salin dari Trip #' . $trip->id . ']%')->exists()
            : false;

        $documentationItems = collect();

        foreach ($trip->documents as $doc) {
            $documentationItems->push([
                'kind'       => $doc->type,
                'title'      => $doc->caption,
                'content'    => $doc->content,
                'file_path'  => $doc->file_path,
                'user_name'  => $doc->user?->name,
                'created_at' => $doc->created_at,
            ]);
        }

        foreach ($trip->days->flatMap->activities->whereNotNull('photo') as $act) {
            $documentationItems->push([
                'kind'       => 'activity_photo',
                'title'      => $act->title,
                'content'    => null,
                'file_path'  => $act->photo,
                'user_name'  => null,
                'created_at' => $act->updated_at,
            ]);
        }

        $documentationItems = $documentationItems->sortByDesc('created_at')->values();
        $hasDocumentation = $documentationItems->isNotEmpty();

        return view('trips.public_show', compact(
            'trip', 'totalEstimatedBudget', 'likeCount', 'isLiked', 'alreadyCloned',
            'documentationItems', 'hasDocumentation'
        ));
    }

    public function cloneToWishlist(Trip $trip)
    {
        if (!$trip->is_public) abort(403);

        $user = Auth::user();

        // Buat trip baru (Wishlist, tanpa tanggal)
        $clone = Trip::create([
            'user_id'      => $user->id,
            'title'        => $trip->title . ' (Salin)',
            'description'  => ($trip->description ?? '') . ' [Salin dari Trip #' . $trip->id . ']',
            'destination'  => $trip->destination,
            'start_date'   => null,
            'end_date'     => null,
            'total_budget' => $trip->total_budget,
            'status'       => 'planning',
            'is_public'    => false,
        ]);

        $clone->members()->attach($user->id, ['role' => 'owner', 'joined_at' => now()]);

        // Salin hari & kegiatan
        foreach ($trip->days as $day) {
            $newDay = $clone->days()->create([
                'day_number' => $day->day_number,
                'date'       => $day->date,
                'note'       => $day->note ?? null,
            ]);

            foreach ($day->activities()->orderBy('sort_order')->get() as $act) {
                $newDay->activities()->create([
                    'title'          => $act->title,
                    'description'    => $act->description,
                    'session'        => $act->session,
                    'start_time'     => $act->start_time,
                    'end_time'       => $act->end_time,
                    'category'       => $act->category,
                    'location_name'  => $act->location_name,
                    'location_url'   => $act->location_url,
                    'estimated_cost' => $act->estimated_cost,
                    'sort_order'     => $act->sort_order,
                    'is_completed'   => false,
                    'actual_cost'    => null,
                    'photo'          => null,
                ]);
            }
        }

        return redirect()->route('trips.index')
            ->with('success', 'Itinerary berhasil disalin ke Wishlist kamu! 📋');
    }

    public function toggleVisibility(Trip $trip)
    {
        if ($trip->user_id !== Auth::id()) abort(403);

        $trip->update(['is_public' => !$trip->is_public]);

        $msg = $trip->is_public ? 'Trip sekarang bisa dilihat publik 🌍' : 'Trip sekarang bersifat privat 🔒';
        return back()->with('success', $msg);
    }
}
