<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['post', 'user']);

        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('author_email', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $comments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
            'is_approved' => 'boolean',
        ]);

        $comment->update($validated);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Комментарий успешно обновлен!');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Комментарий удален!');
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);

        return back()->with('success', 'Комментарий одобрен!');
    }

    public function reject(Comment $comment)
    {
        $comment->update(['is_approved' => false]);

        return back()->with('success', 'Комментарий отклонен!');
    }
}
