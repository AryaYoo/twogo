<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentReport;
use App\Models\Trip;
use Illuminate\Http\Request;

class AdminItineraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with(['creator', 'members'])->withCount(['days', 'activities', 'likes', 'reports']);

        // Filter status
        if ($request->filled('filter')) {
            $filter = $request->input('filter');
            if ($filter === 'published') {
                $query->where('is_public', true);
            } elseif ($filter === 'draft') {
                $query->where('is_public', false)->where('status', '!=', 'completed');
            } elseif ($filter === 'shared') {
                $query->has('members', '>', 1);
            } elseif ($filter === 'flagged') {
                $query->where('is_flagged', true)->orHas('reports');
            }
        }

        // Search title/destination/creator
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhereHas('creator', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $trips = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total'     => Trip::count(),
            'published' => Trip::where('is_public', true)->count(),
            'draft'     => Trip::where('is_public', false)->count(),
            'flagged'   => Trip::where('is_flagged', true)->orHas('reports')->count(),
        ];

        return view('admin.itineraries.index', compact('trips', 'stats'));
    }

    public function show(Trip $trip)
    {
        $trip->load([
            'creator',
            'members',
            'days.activities',
            'expenses',
            'wishlistItems',
            'reports.reporter'
        ]);

        return response()->json([
            'trip'           => $trip,
            'days'           => $trip->days,
            'members'        => $trip->members,
            'expenses_count' => $trip->expenses->count(),
            'total_spent'    => $trip->total_spent,
            'reports'        => $trip->reports,
        ]);
    }

    public function toggleFlag(Request $request, Trip $trip)
    {
        $trip->update([
            'is_flagged' => !$trip->is_flagged,
        ]);

        $statusMessage = $trip->is_flagged 
            ? "Itinerary \"{$trip->title}\" berhasil di-flag untuk moderasi." 
            : "Flag moderasi pada itinerary \"{$trip->title}\" telah dicabut.";

        return back()->with('success', $statusMessage);
    }

    public function reportContent(Request $request, Trip $trip)
    {
        $request->validate([
            'reason'  => 'required|string|max:255',
            'details' => 'nullable|string',
        ]);

        ContentReport::create([
            'trip_id'     => $trip->id,
            'reporter_id' => auth()->id(),
            'reason'      => $request->input('reason'),
            'details'     => $request->input('details'),
            'status'      => 'pending',
        ]);

        $trip->update(['is_flagged' => true]);

        return back()->with('success', 'Laporan moderasi berhasil dikirim.');
    }

    public function destroy(Trip $trip)
    {
        $title = $trip->title;
        $trip->delete();

        return back()->with('success', "Itinerary \"{$title}\" berhasil dihapus dari sistem.");
    }
}
