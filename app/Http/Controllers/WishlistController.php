<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);
        
        $wishlists = $trip->wishlistItems()->orderByDesc('created_at')->get();
        return view('wishlists.index', compact('trip', 'wishlists'));
    }

    public function store(Request $request, Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:wisata,kuliner,belanja,lainnya',
            'priority' => 'required|in:wajib,pengen,kalau_sempat'
        ]);

        WishlistItem::create([
            'trip_id' => $trip->id,
            'added_by' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'location_name' => $request->location_name,
            'location_url' => $request->location_url,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'priority' => $request->priority,
            'votes' => [Auth::id()] // Auto vote from creator
        ]);

        return back()->with('success', 'Wishlist ditambahkan!');
    }

    public function destroy(WishlistItem $wishlist)
    {
        $trip = $wishlist->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $wishlist->delete();
        
        return back()->with('success', 'Wishlist dihapus.');
    }

    public function vote(WishlistItem $wishlist)
    {
        $trip = $wishlist->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $votes = $wishlist->votes ?? [];
        
        if (in_array(Auth::id(), $votes)) {
            $votes = array_diff($votes, [Auth::id()]);
        } else {
            $votes[] = Auth::id();
        }
        
        $wishlist->update(['votes' => array_values($votes)]);
        
        return back();
    }
}
