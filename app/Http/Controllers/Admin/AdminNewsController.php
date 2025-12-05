<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AdminNewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $news = News::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news.
     */
    public function create()
    {
        $authors = User::where('role_id', 1)->get();
        return view('admin.news.create', compact('authors'));
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|string|url',
            'author_id' => 'nullable|exists:users,id',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date_format:Y-m-d H:i',
        ]);

        $validated['slug'] = News::generateSlug($validated['title']);
        $validated['is_published'] = $request->has('is_published');
        $validated['author_id'] = $validated['author_id'] ?? auth()->id();

        if ($validated['is_published'] && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        News::create($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức được tạo thành công!');
    }

    /**
     * Display the specified news.
     */
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(News $news)
    {
        $authors = User::where('role_id', 1)->get();
        return view('admin.news.edit', compact('news', 'authors'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'content' => 'required|string',
            'image_url' => 'nullable|string|url',
            'author_id' => 'nullable|exists:users,id',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date_format:Y-m-d H:i',
        ]);

        if ($validated['title'] !== $news->title) {
            $validated['slug'] = News::generateSlug($validated['title']);
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['author_id'] = $validated['author_id'] ?? auth()->id();

        if ($validated['is_published'] && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức được cập nhật thành công!');
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức được xóa thành công!');
    }

    /**
     * Toggle news published status via API.
     */
    public function togglePublished(News $news)
    {
        $isPublished = !$news->is_published;
        $news->update([
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($news->published_at ?? now()) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái tin tức được cập nhật!',
            'is_published' => $news->is_published,
        ]);
    }
}
