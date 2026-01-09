@extends('admin.layout')

@section('title', 'Редактировать комментарий')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Редактировать комментарий</h2>
    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Назад</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <strong>Статья:</strong> 
            <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank">
                {{ $comment->post->title }}
            </a>
        </div>

        <div class="mb-3">
            <strong>Автор:</strong> 
            @if($comment->user)
                {{ $comment->user->name }} ({{ $comment->user->email }})
            @else
                {{ $comment->author_name ?? 'Аноним' }}
                @if($comment->author_email)
                    ({{ $comment->author_email }})
                @endif
            @endif
        </div>

        <div class="mb-3">
            <strong>Дата создания:</strong> {{ $comment->created_at->format('d.m.Y H:i') }}
        </div>

        <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="content" class="form-label">Содержание комментария *</label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="5" required>{{ old('content', $comment->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if(!$comment->user)
                <div class="mb-3">
                    <label for="author_name" class="form-label">Имя автора</label>
                    <input type="text" class="form-control @error('author_name') is-invalid @enderror" id="author_name" name="author_name" value="{{ old('author_name', $comment->author_name) }}">
                    @error('author_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="author_email" class="form-label">Email автора</label>
                    <input type="email" class="form-control @error('author_email') is-invalid @enderror" id="author_email" name="author_email" value="{{ old('author_email', $comment->author_email) }}">
                    @error('author_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $comment->is_approved) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_approved">
                    Одобрен
                </label>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </form>
    </div>
</div>
@endsection

