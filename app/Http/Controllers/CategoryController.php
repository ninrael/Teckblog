<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        $posts = $category->posts()
            ->where('is_published', true)
            ->with(['tags', 'user'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('categories.show', compact('category', 'posts'));
    }

    public function showTag(Tag $tag, Request $request)
    {
        $posts = $tag->posts()
            ->where('is_published', true)
            ->with(['category', 'user'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('tags.show', compact('tag', 'posts'));
    }
}
