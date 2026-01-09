@extends('admin.layout')

@section('title', 'Статьи')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление статьями</h2>
    <div class="btn-group">
        <a href="{{ route('admin.posts.index', ['status' => 'all']) }}" class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">Все</a>
        <a href="{{ route('admin.posts.index', ['status' => 'published']) }}" class="btn btn-outline-success {{ request('status') === 'published' ? 'active' : '' }}">Опубликованные</a>
        <a href="{{ route('admin.posts.index', ['status' => 'pending']) }}" class="btn btn-outline-warning {{ request('status') === 'pending' ? 'active' : '' }}">На модерации</a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Автор</th>
                        <th>Категория</th>
                        <th>Статус</th>
                        <th>Просмотры</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" target="_blank" class="text-decoration-none">
                                    {{ $post->title }}
                                </a>
                            </td>
                            <td>{{ $post->user->name }}</td>
                            <td><span class="badge bg-primary">{{ $post->category->name }}</span></td>
                            <td>
                                @if($post->is_published)
                                    <span class="badge bg-success">Опубликовано</span>
                                @else
                                    <span class="badge bg-warning">На модерации</span>
                                @endif
                            </td>
                            <td>{{ $post->views }}</td>
                            <td>{{ $post->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary btn-sm">Редактировать</a>
                                    @if($post->is_published)
                                        <form action="{{ route('admin.posts.unpublish', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Снять с публикации</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.posts.publish', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Опубликовать</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить статью?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

