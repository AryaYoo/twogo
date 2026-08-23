<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('news.index', compact('newsList'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $recentNews = News::where('is_published', true)
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'recentNews'));
    }
}
