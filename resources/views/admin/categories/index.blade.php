@extends('admin.layout')

@section('title', 'Категории')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление категориями</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Создать категорию</a>
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
                        <th>Описание</th>
                        <th>Постов</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>{{ $category->description ? \Illuminate\Support\Str::limit($category->description, 50) : '-' }}</td>
                            <td><span class="badge bg-primary">{{ $category->posts_count }}</span></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">Редактировать</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить категорию?')">
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
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection

