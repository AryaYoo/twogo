<?php

namespace App\Http\Controllers;

use App\Models\TripActivity;
use App\Models\TripDay;
use App\Models\Expense;
use App\Models\ExpenseSplit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TripActivityController extends Controller
{
    public function store(Request $request, TripDay $day)
    {
        $trip = $day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'session' => 'required|in:pagi,siang,malam',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'category' => 'required|in:wisata,kuliner,transportasi,akomodasi,belanja,lainnya',
            'estimated_cost' => 'nullable|numeric|min:0'
        ]);

        $maxSort = $day->activities()->where('session', $request->session)->max('sort_order') ?? 0;

        TripActivity::create([
            'trip_day_id' => $day->id,
            'title' => $request->title,
            'description' => $request->description,
            'session' => $request->session,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location_name' => $request->location_name,
            'location_url' => $request->location_url,
            'category' => $request->category,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'Kegiatan ditambahkan!');
    }

    public function update(Request $request, TripActivity $activity)
    {
        $trip = $activity->day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'session' => 'required|in:pagi,siang,malam',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'category' => 'required|in:wisata,kuliner,transportasi,akomodasi,belanja,lainnya'
        ]);

        $activity->update($request->only([
            'title','description','session','start_time','end_time','category','location_name','location_url','estimated_cost'
        ]));

        return back()->with('success', 'Kegiatan diupdate!');
    }

    public function toggleComplete(TripActivity $activity)
    {
        $trip = $activity->day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        if ($activity->is_completed) {
            // Uncheck action: delete photo & actual_cost
            if ($activity->photo) {
                Storage::disk('public')->delete($activity->photo);
                Storage::disk('public')->delete(dirname($activity->photo) . '/thumb_' . basename($activity->photo));
            }
            
            // Delete associated expense
            $expense = \App\Models\Expense::where('trip_id', $trip->id)
                ->where('title', $activity->title)
                ->where('notes', 'Dari kegiatan: ' . $activity->title)
                ->first();
                
            if ($expense) {
                $expense->splits()->delete();
                $expense->delete();
            }

            $activity->update([
                'is_completed' => false,
                'photo' => null,
                'actual_cost' => null
            ]);

            return back()->with('success', 'Kegiatan batal diselesaikan. Catatan pengeluaran & foto telah dihapus.');
        }

        // Fallback if checking directly (though usually handled by 'complete' method via modal)
        $activity->update(['is_completed' => true]);
        return back();
    }

    public function complete(Request $request, TripActivity $activity)
    {
        $trip = $activity->day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'photo' => 'nullable|image|max:20480',
            'actual_cost' => 'required|numeric|min:0',
        ], [
            'photo.max' => 'Ukuran foto terlalu besar (Maksimal 20MB).'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('activities', 'public');
            
            // Compress main photo and create thumbnail
            try {
                $manager = new ImageManager(new Driver());
                $fullPath = storage_path('app/public/' . $photoPath);
                $thumbPath = storage_path('app/public/activities/thumb_' . basename($photoPath));
                
                $image = $manager->read($fullPath);
                
                // 1. Compress main photo (max 1920px width for lighter High-Res)
                $image->scaleDown(width: 1920);
                $image->save($fullPath, quality: 80);
                
                // 2. Create small thumbnail (max 400px width for grid)
                $image->scaleDown(width: 400);
                $image->save($thumbPath, quality: 60);
            } catch (\Exception $e) {
                // If compression fails, just continue
                \Illuminate\Support\Facades\Log::error("Image compression failed: " . $e->getMessage());
            }
        }

        DB::transaction(function() use ($request, $activity, $trip, $photoPath) {
            $activity->update([
                'is_completed' => true,
                'photo' => $photoPath,
                'actual_cost' => $request->actual_cost
            ]);

            if ($request->actual_cost > 0) {
                // Determine split type
                $splitType = ($request->has('split_bill') && $trip->members->count() > 1) ? 'equal' : 'solo';
                
                // Map category
                $catMapping = ['wisata' => 'tiket', 'kuliner' => 'makanan'];
                $expenseCat = $catMapping[$activity->category] ?? (in_array($activity->category, ['akomodasi','transportasi','belanja','lainnya']) ? $activity->category : 'lainnya');

                $expense = Expense::create([
                    'trip_id' => $trip->id,
                    'paid_by' => Auth::id(),
                    'title' => $activity->title,
                    'amount' => $request->actual_cost,
                    'category' => $expenseCat,
                    'expense_date' => $activity->day->date,
                    'split_type' => $splitType,
                    'notes' => 'Dari kegiatan: ' . $activity->title,
                    'receipt_image' => $photoPath,
                ]);

                if ($splitType === 'equal') {
                    $memberCount = $trip->members->count();
                    $splitAmount = $request->actual_cost / $memberCount;
                    
                    foreach ($trip->members as $member) {
                        ExpenseSplit::create([
                            'expense_id' => $expense->id,
                            'user_id' => $member->id,
                            'amount' => $splitAmount,
                            'is_settled' => $member->id === Auth::id()
                        ]);
                    }
                } else {
                    ExpenseSplit::create([
                        'expense_id' => $expense->id,
                        'user_id' => Auth::id(),
                        'amount' => $request->actual_cost,
                        'is_settled' => true
                    ]);
                }
            }
        });

        return back()->with('success', 'Kegiatan selesai dan budget dicatat!');
    }

    public function destroy(TripActivity $activity)
    {
        $trip = $activity->day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        if ($activity->photo) {
            Storage::disk('public')->delete($activity->photo);
            Storage::disk('public')->delete(dirname($activity->photo) . '/thumb_' . basename($activity->photo));
        }

        $activity->delete();
        
        return back()->with('success', 'Kegiatan dihapus.');
    }
}
