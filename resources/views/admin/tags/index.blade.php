@extends('admin.layout')

@section('title', 'Теги')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление тегами</h2>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Создать тег</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Slug</th>
                        <th>Постов</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td><strong>{{ $tag->name }}</strong></td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td><span class="badge bg-primary">{{ $tag->posts_count }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-primary">Редактировать</a>
                                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить тег?')">
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
            {{ $tags->links() }}
        </div>
    </div>
</div>
@endsection

