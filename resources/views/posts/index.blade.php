@extends('layouts.app')

@section('title', 'Главная')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
@php
    $sidebarPosition = \App\Models\Setting::get('sidebar_position', 'right');
    $isSidebarLeft = $sidebarPosition === 'left';
@endphp
<div class="row">
    @if($isSidebarLeft)
        <aside class="col-lg-4 order-lg-1 order-2">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Категории</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($categories as $category)
                            <li class="mb-2">
                                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                                    {{ $category->name }} 
                                    <span class="badge bg-secondary">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Теги</h5>
                </div>
                <div class="card-body">
                    @foreach($tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1 mb-1">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Топ статьи</h5>
                </div>
                <div class="card-body">
                    @forelse($topPosts as $topPost)
                        <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('posts.show', $topPost->slug) }}" class="text-decoration-none">
                                    {{ $topPost->title }}
                                </a>
                            </h6>
                            <small class="text-muted d-block">
                                <span class="badge bg-primary">{{ $topPost->category->name }}</span>
                                <span class="ms-2">👁 {{ $topPost->views }}</span>
                            </small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Нет популярных статей</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Комментарии</h5>
                </div>
                <div class="card-body">
                    @forelse($recentComments as $comment)
                        <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1">
                                        <strong>{{ $comment->user ? $comment->user->name : $comment->author_name }}</strong>
                                        @if($comment->post)
                                            к статье: 
                                            <a href="{{ route('posts.show', $comment->post->slug) }}" class="text-decoration-none">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        @endif
                                    </small>
                                    <p class="mb-0 small">{{ Str::limit($comment->content, 60) }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Нет комментариев</p>
                    @endforelse
                </div>
            </div>
        </aside>
    @endif

    <div class="col-lg-8 {{ $isSidebarLeft ? 'order-lg-2 order-1' : '' }}">
        <h1 class="mb-4">Последние статьи</h1>
        
        @forelse($posts as $post)
            <article class="card mb-4 post-card">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 300px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                        @foreach($post->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <h2 class="card-title">
                        <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <p class="card-text">{{ $post->excerpt }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Автор: {{ $post->user->name }} | 
                            {{ $post->published_at->format('d.m.Y') }} | 
                            Просмотров: {{ $post->views }}
                        </small>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary">Читать далее</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="alert alert-info">
                Пока нет опубликованных статей.
            </div>
        @endforelse

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>

    @if(!$isSidebarLeft)
        <aside class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Категории</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($categories as $category)
                            <li class="mb-2">
                                <a href="{{ route('categories.show', $category->slug) }}" class="text-decoration-none">
                                    {{ $category->name }} 
                                    <span class="badge bg-secondary">{{ $category->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Теги</h5>
                </div>
                <div class="card-body">
                    @foreach($tags as $tag)
                        <a href="{{ route('tags.show', $tag->slug) }}" class="badge bg-secondary text-decoration-none me-1 mb-1">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Топ статьи</h5>
                </div>
                <div class="card-body">
                    @forelse($topPosts as $topPost)
                        <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <h6 class="mb-1">
                                <a href="{{ route('posts.show', $topPost->slug) }}" class="text-decoration-none">
                                    {{ $topPost->title }}
                                </a>
                            </h6>
                            <small class="text-muted d-block">
                                <span class="badge bg-primary">{{ $topPost->category->name }}</span>
                                <span class="ms-2">👁 {{ $topPost->views }}</span>
                            </small>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Нет популярных статей</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Комментарии</h5>
                </div>
                <div class="card-body">
                    @forelse($recentComments as $comment)
                        <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block mb-1">
                                        <strong>{{ $comment->user ? $comment->user->name : $comment->author_name }}</strong>
                                        @if($comment->post)
                                            к статье: 
                                            <a href="{{ route('posts.show', $comment->post->slug) }}" class="text-decoration-none">
                                                {{ Str::limit($comment->post->title, 30) }}
                                            </a>
                                        @endif
                                    </small>
                                    <p class="mb-0 small">{{ Str::limit($comment->content, 60) }}</p>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Нет комментариев</p>
                    @endforelse
                </div>
            </div>
        </aside>
    @endif
</div>
@endsection

