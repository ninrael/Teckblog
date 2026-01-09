@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #2c3e50;">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" viewBox="0 0 16 16" class="profile-icon">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                        </svg>
                    @endif
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                @if($user->role)
                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                @endif
                <div class="mt-3">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">Редактировать профиль</a>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Статистика</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Статей:</span>
                    <strong>{{ $stats['posts_count'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Опубликовано:</span>
                    <strong>{{ $stats['published_posts'] }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Комментариев:</span>
                    <strong>{{ $stats['comments_count'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Информация о профиле</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Имя:</label>
                    <p class="mb-0">{{ $user->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Роль:</label>
                    <p class="mb-0">
                        @if($user->role)
                            {{ $user->role->name }}
                        @else
                            <span class="text-muted">Не назначена</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Дата регистрации:</label>
                    <p class="mb-0">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                </div>
                @if($user->is_banned)
                    <div class="alert alert-danger">
                        <strong>Аккаунт заблокирован</strong><br>
                        @if($user->ban_reason)
                            Причина: {{ $user->ban_reason }}
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Последние статьи</h5>
            </div>
            <div class="card-body">
                @forelse($user->posts()->latest()->take(5)->get() as $post)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                            <br>
                            <small class="text-muted">
                                {{ $post->created_at->format('d.m.Y') }} | 
                                @if($post->is_published)
                                    <span class="badge bg-success">Опубликовано</span>
                                @else
                                    <span class="badge bg-warning">На модерации</span>
                                @endif
                            </small>
                        </div>
                        <div>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">У вас пока нет статей</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

