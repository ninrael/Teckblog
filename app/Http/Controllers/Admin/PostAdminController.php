<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'category', 'tags']);

        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_published', false);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
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

        return redirect()->route('admin.posts.index')
            ->with('success', 'Пост успешно обновлен!');
    }

    public function publish(Post $post)
    {
        $post->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Пост опубликован!');
    }

    public function unpublish(Post $post)
    {
        $post->update(['is_published' => false]);

        return back()->with('success', 'Пост снят с публикации!');
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            \Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();

        return back()->with('success', 'Пост удален!');
    }
}
