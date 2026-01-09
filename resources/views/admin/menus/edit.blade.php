@extends('admin.layout')

@section('title', 'Редактировать пункт меню')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Редактировать пункт меню</h2>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Назад</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Название *</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $menu->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Тип *</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required onchange="toggleTypeFields()">
                    <option value="custom" {{ old('type', $menu->type) === 'custom' ? 'selected' : '' }}>Произвольная ссылка</option>
                    <option value="category" {{ old('type', $menu->type) === 'category' ? 'selected' : '' }}>Категория</option>
                    <option value="tag" {{ old('type', $menu->type) === 'tag' ? 'selected' : '' }}>Тег</option>
                    <option value="page" {{ old('type', $menu->type) === 'page' ? 'selected' : '' }}>Страница</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="url-field">
                <label for="url" class="form-label">URL *</label>
                <input type="url" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url', $menu->url) }}" placeholder="https://example.com">
                @error('url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="category-field" style="display: none;">
                <label for="category_id" class="form-label">Категория *</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="tag-field" style="display: none;">
                <label for="tag_id" class="form-label">Тег *</label>
                <select class="form-select @error('tag_id') is-invalid @enderror" id="tag_id" name="tag_id">
                    <option value="">Выберите тег</option>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ old('tag_id', $menu->tag_id) == $tag->id ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                @error('tag_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3" id="page-field" style="display: none;">
                <label for="page_id" class="form-label">Страница *</label>
                <select class="form-select @error('page_id') is-invalid @enderror" id="page_id" name="page_id">
                    <option value="">Выберите страницу</option>
                    @php
                        // Определяем выбранную страницу для системных страниц
                        $selectedPageId = old('page_id', $menu->page_id);
                        if ($menu->type === 'page' && !$menu->page_id) {
                            // Проверяем URL для определения системной страницы
                            if (str_contains($menu->url, route('about.show'))) {
                                $selectedPageId = 'system_about';
                            } elseif (str_contains($menu->url, route('policy.terms'))) {
                                $selectedPageId = 'system_terms';
                            } elseif (str_contains($menu->url, route('policy.privacy'))) {
                                $selectedPageId = 'system_privacy';
                            }
                        }
                    @endphp
                    @if($pages->isNotEmpty())
                        @if($pages->where('is_system', true)->isNotEmpty())
                            <optgroup label="Системные страницы">
                                @foreach($pages->where('is_system', true) as $page)
                                    <option value="{{ $page->id }}" {{ $selectedPageId == $page->id ? 'selected' : '' }}>
                                        {{ $page->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                        @if($pages->where('is_system', false)->isNotEmpty())
                            <optgroup label="Обычные страницы">
                                @foreach($pages->where('is_system', false) as $page)
                                    <option value="{{ $page->id }}" {{ $selectedPageId == $page->id ? 'selected' : '' }}>
                                        {{ $page->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    @else
                        <option value="" disabled>Нет доступных страниц</option>
                    @endif
                </select>
                @error('page_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($pages->isEmpty())
                    <small class="text-muted">Создайте страницы в разделе "Страницы" для добавления их в меню</small>
                @endif
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">Родительское меню</label>
                <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                    <option value="">Нет (корневой пункт)</option>
                    @foreach($parentMenus as $parentMenu)
                        <option value="{{ $parentMenu->id }}" {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
                            {{ $parentMenu->title }}
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Выберите родительский пункт меню, чтобы создать подменю</small>
                @error('parent_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="order" class="form-label">Порядок</label>
                <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $menu->order) }}" min="0">
                @error('order')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Активно
                </label>
            </div>

            <div class="mb-3">
                <label for="target" class="form-label">Открывать в</label>
                <select class="form-select" id="target" name="target">
                    <option value="_self" {{ old('target', $menu->target) === '_self' ? 'selected' : '' }}>Текущей вкладке</option>
                    <option value="_blank" {{ old('target', $menu->target) === '_blank' ? 'selected' : '' }}>Новой вкладке</option>
                </select>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const urlField = document.getElementById('url-field');
    const categoryField = document.getElementById('category-field');
    const tagField = document.getElementById('tag-field');
    const pageField = document.getElementById('page-field');
    
    urlField.style.display = type === 'custom' ? 'block' : 'none';
    categoryField.style.display = type === 'category' ? 'block' : 'none';
    tagField.style.display = type === 'tag' ? 'block' : 'none';
    pageField.style.display = type === 'page' ? 'block' : 'none';
    
    if (type === 'custom') {
        document.getElementById('url').required = true;
        document.getElementById('category_id').required = false;
        document.getElementById('tag_id').required = false;
        document.getElementById('page_id').required = false;
    } else if (type === 'category') {
        document.getElementById('url').required = false;
        document.getElementById('category_id').required = true;
        document.getElementById('tag_id').required = false;
        document.getElementById('page_id').required = false;
    } else if (type === 'tag') {
        document.getElementById('url').required = false;
        document.getElementById('category_id').required = false;
        document.getElementById('tag_id').required = true;
        document.getElementById('page_id').required = false;
    } else if (type === 'page') {
        document.getElementById('url').required = false;
        document.getElementById('category_id').required = false;
        document.getElementById('tag_id').required = false;
        document.getElementById('page_id').required = true;
    }
}

// Вызываем при загрузке страницы
document.addEventListener('DOMContentLoaded', toggleTypeFields);
</script>
@endsection

