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

    public function splitBudget(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $members = $trip->members()->pluck('id')->toArray();
        $count = count($members);
        if ($count < 2) {
            return back()->with('error', 'Tidak ada anggota lain untuk membagi budget.');
        }

        // Only allow splitting once
        if ($trip->expenses()->where('title', 'Split Budget')->exists()) {
            return back()->with('info', 'Budget sudah dibagi.');
        }

        $total = (float) $trip->total_budget;
        if ($total <= 0) return back()->with('error', 'Total budget tidak ditentukan.');

        $share = round($total / $count, 2);

        // create expense paid by trip owner
        $expense = \App\Models\Expense::create([
            'trip_id' => $trip->id,
            'paid_by' => $trip->user_id,
            'title' => 'Split Budget',
            'amount' => $total,
            'category' => 'budget',
            'split_type' => 'equal',
            'expense_date' => now()->format('Y-m-d'),
        ]);

        foreach ($members as $userId) {
            \App\Models\ExpenseSplit::create([
                'expense_id' => $expense->id,
                'user_id' => $userId,
                'amount' => $share,
            ]);
        }

        return back()->with('success', 'Budget berhasil dibagi ke anggota.');
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
}
