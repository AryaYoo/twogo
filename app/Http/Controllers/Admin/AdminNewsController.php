<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $newsList = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.news.index', compact('newsList'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author'  => 'nullable|string|max:100',
        ]);

        News::create([
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . Str::random(4),
            'excerpt'      => $request->excerpt,
            'content'      => $request->content,
            'author'       => $request->author ?? 'Tim TwoGo',
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil ditambahkan!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author'  => 'nullable|string|max:100',
        ]);

        $news->update([
            'title'        => $request->title,
            'excerpt'      => $request->excerpt,
            'content'      => $request->content,
            'author'       => $request->author ?? 'Tim TwoGo',
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? ($news->published_at ?? now()) : null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil diperbarui!');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Artikel berita berhasil dihapus!');
    }
}
