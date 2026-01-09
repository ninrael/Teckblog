<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update(['role_id' => $request->role_id]);

        return back()->with('success', 'Роль пользователя обновлена!');
    }

    public function ban(User $user)
    {
        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
        ]);

        return back()->with('success', 'Пользователь забанен!');
    }

    public function unban(User $user)
    {
        $user->update([
            'is_banned' => false,
            'banned_at' => null,
            'ban_reason' => null,
        ]);

        return back()->with('success', 'Пользователь разбанен!');
    }

    public function updateBanReason(Request $request, User $user)
    {
        $request->validate([
            'ban_reason' => 'nullable|string|max:500',
        ]);

        $user->update(['ban_reason' => $request->ban_reason]);

        return back()->with('success', 'Причина бана обновлена!');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin() && User::whereHas('role', function($q) {
            $q->where('slug', 'admin');
        })->count() <= 1) {
            return back()->with('error', 'Нельзя удалить последнего администратора!');
        }

        $user->delete();

        return back()->with('success', 'Пользователь удален!');
    }
}
