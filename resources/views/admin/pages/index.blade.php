@extends('admin.layout')

@section('title', 'Страницы')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Страницы</h2>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Создать страницу</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Поиск..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Все статусы</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Опубликованные</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Черновики</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Поиск</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary w-100">Сбросить</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>URL</th>
                        <th>Статус</th>
                        <th>Создано</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Системные страницы --}}
                    @foreach($systemPages as $systemPage)
                        <tr style="background-color: #f8f9fa;">
                            <td><span class="badge bg-info">Системная</span></td>
                            <td>
                                <strong>{{ $systemPage['title'] }}</strong>
                                <span class="badge bg-secondary ms-2">Системная</span>
                            </td>
                            <td>
                                <a href="{{ $systemPage['url'] }}" target="_blank" class="text-decoration-none">
                                    /{{ $systemPage['slug'] }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-success">Опубликована</span>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($systemPage['edit_route'])
                                        <a href="{{ route($systemPage['edit_route']) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    @endif
                                    <a href="{{ $systemPage['url'] }}" target="_blank" class="btn btn-sm btn-outline-info">Просмотр</a>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Системная страница нельзя удалить">Удалить</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    
                    {{-- Обычные страницы --}}
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>{{ $page->title }}</td>
                            <td>
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-decoration-none">
                                    /pages/{{ $page->slug }}
                                </a>
                            </td>
                            <td>
                                @if($page->is_published)
                                    <span class="badge bg-success">Опубликована</span>
                                @else
                                    <span class="badge bg-secondary">Черновик</span>
                                @endif
                            </td>
                            <td>{{ $page->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Редактировать</a>
                                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-info">Просмотр</a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить страницу?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @if(count($systemPages) == 0)
                            <tr>
                                <td colspan="6" class="text-center">Страницы не найдены</td>
                            </tr>
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection

