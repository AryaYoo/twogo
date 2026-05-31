<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);
        
        $documents = $trip->documents()->latest()->get();
        return view('documents.index', compact('trip', 'documents'));
    }

    public function store(Request $request, Trip $trip)
    {
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        $request->validate([
            'type' => 'required|in:photo,note',
            'caption' => 'nullable|string|max:255',
            'content' => 'required_if:type,note|nullable|string',
            'photo' => 'required_if:type,photo|image|max:5120', // max 5MB
        ]);

        $path = null;
        if ($request->type === 'photo' && $request->hasFile('photo')) {
            $path = $request->file('photo')->store('trip_documents', 'public');
        }

        TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'file_path' => $path,
            'caption' => $request->caption,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Dokumentasi berhasil ditambahkan!');
    }

    public function destroy(TripDocument $document)
    {
        $trip = $document->trip;
        if (!$trip->members()->where('user_id', Auth::id())->exists()) abort(403);

        if ($document->type === 'photo' && $document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
        
        return back()->with('success', 'Dokumen dihapus.');
    }
}
