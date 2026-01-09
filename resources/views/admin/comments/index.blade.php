@extends('admin.layout')

@section('title', 'Комментарии')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление комментариями</h2>
    <div class="btn-group">
        <a href="{{ route('admin.comments.index', ['status' => 'all']) }}" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">Все</a>
        <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" class="btn btn-outline-success {{ request('status') === 'approved' ? 'active' : '' }}">Одобренные</a>
        <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="btn btn-outline-warning {{ request('status') === 'pending' ? 'active' : '' }}">На модерации</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Автор</th>
                        <th>Статья</th>
                        <th>Комментарий</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>
                                @if($comment->user)
                                    <strong>{{ $comment->user->name }}</strong><br>
                                    <small class="text-muted">{{ $comment->user->email }}</small>
                                @else
                                    <strong>{{ $comment->author_name ?? 'Аноним' }}</strong><br>
                                    @if($comment->author_email)
                                        <small class="text-muted">{{ $comment->author_email }}</small>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank" class="text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($comment->content, 100) }}</td>
                            <td>
                                @if($comment->is_approved)
                                    <span class="badge bg-success">Одобрен</span>
                                @else
                                    <span class="badge bg-warning">На модерации</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-primary">Редактировать</a>
                                    @if($comment->is_approved)
                                        <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning">Отклонить</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Одобрить</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить комментарий?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $comments->links() }}
        </div>
    </div>
</div>
@endsection

