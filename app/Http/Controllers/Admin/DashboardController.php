<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_banned', false)->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('is_published', true)->count(),
            'pending_posts' => Post::where('is_published', false)->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
            'total_categories' => Category::count(),
        ];

        $recent_posts = Post::with(['user', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recent_users = User::with('role')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_posts', 'recent_users'));
    }
}
