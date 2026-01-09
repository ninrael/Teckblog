@extends('layouts.app')

@section('title', $post->title)

@section('content')
@php
    $showSidebar = \App\Models\Setting::get('sidebar_on_post', true);
    $sidebarPosition = \App\Models\Setting::get('sidebar_position', 'right');
    $isSidebarLeft = $sidebarPosition === 'left';
@endphp
<div class="row">
    @if($showSidebar && $isSidebarLeft)
        <aside class="col-lg-4 order-lg-1 order-2 mb-4">
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

            <div class="card">
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
        </aside>
    @endif

    <div class="{{ $showSidebar ? 'col-lg-8' : 'col-12' }} {{ $showSidebar && $isSidebarLeft ? 'order-lg-2 order-1' : '' }}">
<article>
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $post->category->slug) }}">{{ $post->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $post->title }}</li>
        </ol>
    </nav>

    @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}" class="img-fluid rounded mb-4" alt="{{ $post->title }}">
    @endif

    <div class="mb-3">
        <span class="badge bg-primary">{{ $post->category->name }}</span>
        @foreach($post->tags as $tag)
            <span class="badge bg-secondary">{{ $tag->name }}</span>
        @endforeach
    </div>

    <h1 class="mb-3">{{ $post->title }}</h1>

    <div class="text-muted mb-4">
        <small>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
            Автор: <strong>{{ $post->user->name }}</strong> | 
            Опубликовано: {{ $post->published_at->format('d.m.Y H:i') }} | 
            Просмотров: {{ $post->views }} |
            <span class="likes-section">
                <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none like-btn" data-post-slug="{{ $post->slug }}" style="color: inherit;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="{{ auth()->check() && $post->isLikedBy(auth()->user()) ? '#dc3545' : 'currentColor' }}" viewBox="0 0 16 16" class="me-1">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                    <span class="likes-count">{{ $post->likes->count() }}</span>
                </button>
            </span>
        </small>
    </div>

    <div class="post-content mb-5">
        {!! $post->content !!}
    </div>

    <hr class="my-5">

    <h3 class="mb-4">Комментарии ({{ $post->approvedComments->count() }})</h3>

    @auth
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Добавить комментарий</h5>
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
        </div>
    @endauth

    <div class="comments">
        @forelse($post->approvedComments as $comment)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-subtitle mb-0 text-muted">
                            {{ $comment->user ? $comment->user->name : $comment->author_name }}
                            <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                        </h6>
                        @auth
                            @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить комментарий?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <p class="card-text">{{ $comment->content }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Пока нет комментариев. Будьте первым!</p>
        @endforelse
    </div>

    @if($relatedPosts->count() > 0)
        <hr class="my-5">
        <h3 class="mb-4">Похожие статьи</h3>
        <div class="row">
            @foreach($relatedPosts as $relatedPost)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        @if($relatedPost->featured_image)
                            <img src="{{ asset('storage/' . $relatedPost->featured_image) }}" class="card-img-top" alt="{{ $relatedPost->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('posts.show', $relatedPost->slug) }}" class="text-decoration-none">
                                    {{ $relatedPost->title }}
                                </a>
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</article>
    </div>

    @if($showSidebar && !$isSidebarLeft)
        <aside class="col-lg-4 mb-4">
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

            <div class="card">
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
        </aside>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            @auth
                const postSlug = this.dataset.postSlug;
                const likeIcon = this.querySelector('svg');
                const likesCount = this.querySelector('.likes-count');
                
                fetch(`/posts/${postSlug}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.liked) {
                        likeIcon.setAttribute('fill', '#dc3545');
                    } else {
                        likeIcon.setAttribute('fill', 'currentColor');
                    }
                    likesCount.textContent = data.likes_count;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            @else
                window.location.href = '{{ route("login") }}';
            @endauth
        });
    });
});
</script>
@endpush
@endsection

