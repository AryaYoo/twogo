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
        $trips = Auth::user()->trips()->orderByDesc('created_at')->get();
        return view('trips.index', compact('trips'));
    }

    public function create()
    {
        return view('trips.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_budget' => 'nullable|numeric|min:0',
        ]);

        $trip = Trip::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'destination' => $request->destination,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_budget' => $request->total_budget ?? 0,
            'invite_code' => strtoupper(Str::random(6)),
            'status' => 'planning'
        ]);

        $trip->members()->attach(Auth::id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        // Generate days
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $daysCount = $start->diffInDays($end) + 1;

        for ($i = 0; $i < $daysCount; $i++) {
            TripDay::create([
                'trip_id' => $trip->id,
                'date' => $start->copy()->addDays($i)->format('Y-m-d'),
                'day_number' => $i + 1
            ]);
        }

        return redirect()->route('trips.show', $trip)->with('success', 'Trip berhasil dibuat! Ayo susun timeline.');
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
}
