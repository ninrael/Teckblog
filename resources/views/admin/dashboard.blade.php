@extends('admin.layout')

@section('title', 'Дашборд')

@section('content')
<div class="admin-header text-white mb-4">
    <h1 class="mb-0">Панель управления</h1>
    <p class="mb-0">Добро пожаловать, {{ Auth::user()->name }}!</p>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Пользователи</h5>
                <h2 class="text-primary">{{ $stats['total_users'] }}</h2>
                <small class="text-muted">Активных: {{ $stats['active_users'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Статьи</h5>
                <h2 class="text-success">{{ $stats['published_posts'] }}</h2>
                <small class="text-muted">На модерации: {{ $stats['pending_posts'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Комментарии</h5>
                <h2 class="text-info">{{ $stats['total_comments'] }}</h2>
                <small class="text-muted">Ожидают: {{ $stats['pending_comments'] }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Категории</h5>
                <h2 class="text-warning">{{ $stats['total_categories'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Последние статьи</h5>
            </div>
            <div class="card-body">
                @forelse($recent_posts as $post)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                                {{ $post->title }}
                            </a>
                            <br>
                            <small class="text-muted">
                                {{ $post->user->name }} | 
                                {{ $post->created_at->format('d.m.Y') }}
                            </small>
                        </div>
                        <div>
                            @if($post->is_published)
                                <span class="badge bg-success">Опубликовано</span>
                            @else
                                <span class="badge bg-warning">На модерации</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Нет статей</p>
                @endforelse
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary mt-2">Все статьи</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Последние пользователи</h5>
            </div>
            <div class="card-body">
                @forelse($recent_users as $user)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div>
                            @if($user->role)
                                <span class="badge bg-primary">{{ $user->role->name }}</span>
                            @endif
                            @if($user->is_banned)
                                <span class="badge bg-danger">Забанен</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Нет пользователей</p>
                @endforelse
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary mt-2">Все пользователи</a>
            </div>
        </div>
    </div>
</div>
@endsection

