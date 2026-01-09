<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:255',
            'author_email' => 'nullable|email|max:255',
        ]);

        $validated['post_id'] = $post->id;
        
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $validated['is_approved'] = true; // Автоматически одобряем комментарии авторизованных пользователей
        }

        Comment::create($validated);

        return back()->with('success', 'Комментарий добавлен! ' . 
            (auth()->check() ? '' : 'Он будет опубликован после модерации.'));
    }

    public function destroy(Comment $comment)
    {
        // Проверяем права: автор комментария или администратор
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав для удаления этого комментария.');
        }

        $comment->delete();

        return back()->with('success', 'Комментарий удален!');
    }
}
