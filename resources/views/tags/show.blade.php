@extends('layouts.app')

@section('title', 'Тег: ' . $tag->name)

@section('content')
<h1 class="mb-4">Тег: {{ $tag->name }}</h1>

<div class="row">
    @forelse($posts as $post)
        <div class="col-md-6 mb-4">
            <article class="card post-card h-100">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-primary">{{ $post->category->name }}</span>
                        @foreach($post->tags as $postTag)
                            @if($postTag->id != $tag->id)
                                <span class="badge bg-secondary">{{ $postTag->name }}</span>
                            @endif
                        @endforeach
                    </div>
                    <h3 class="card-title">
                        <a href="{{ route('posts.show', $post->slug) }}" class="text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h3>
                    <p class="card-text">{{ $post->excerpt }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            {{ $post->published_at->format('d.m.Y') }} | 
                            Просмотров: {{ $post->views }}
                        </small>
                        <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-primary">Читать</a>
                    </div>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                Постов с этим тегом пока нет.
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $posts->links() }}
</div>
@endsection

