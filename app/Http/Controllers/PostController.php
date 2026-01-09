<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::where('is_published', true)
            ->with(['category', 'tags', 'user']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')->paginate(12)->withQueryString();

        $categories = Category::withCount('posts')->get();
        $tags = Tag::withCount('posts')->get();
        
        // Топ 3 статьи по просмотрам
        $topPosts = Post::where('is_published', true)
            ->with(['category', 'user'])
            ->orderBy('views', 'desc')
            ->take(3)
            ->get();
        
        // Последние комментарии
        $recentComments = Comment::where('is_approved', true)
            ->with(['post', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('posts.index', compact('posts', 'categories', 'tags', 'topPosts', 'recentComments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if ($validated['is_published'] ?? false) {
            $validated['published_at'] = now();
        }

        $post = Post::create($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Пост успешно создан!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!$post->is_published) {
            abort(404);
        }
        
        $post->load(['category', 'tags', 'user', 'approvedComments.user', 'likes']);

        // Увеличиваем счетчик просмотров
        $post->increment('views');

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Получаем категории и теги для сайдбара
        $categories = Category::withCount('posts')->get();
        $tags = Tag::withCount('posts')->get();

        return view('posts.show', compact('post', 'relatedPosts', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Проверяем права доступа
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        
        $categories = Category::all();
        $tags = Tag::all();
        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Проверяем права доступа
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                \Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        if ($validated['is_published'] ?? false && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Пост успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Проверяем права доступа
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
        
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Пост успешно удален!');
    }
}
