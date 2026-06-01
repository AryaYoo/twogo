<?php

namespace App\Http\Controllers;

use App\Models\TripActivity;
use App\Models\TripDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $activity->update(['is_completed' => !$activity->is_completed]);
        
        return back();
    }

    public function destroy(TripActivity $activity)
    {
        $trip = $activity->day->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $activity->delete();
        
        return back()->with('success', 'Kegiatan dihapus.');
    }
}
