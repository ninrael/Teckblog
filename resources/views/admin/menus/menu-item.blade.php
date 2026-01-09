<li class="list-group-item menu-item" data-menu-id="{{ $menu->id }}">
    <div class="d-flex align-items-center">
        <span class="menu-handle">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.5 2.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H4a.5.5 0 0 1-.5-.5v-1zM3 5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-8A.5.5 0 0 1 3 6V5zm.5 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V8a.5.5 0 0 0-.5-.5h-8z"/>
            </svg>
        </span>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $menu->title }}</strong>
                    <span class="badge bg-secondary ms-2">Порядок: {{ $menu->order }}</span>
                    @if($menu->type === 'category')
                        <span class="badge bg-info ms-1">Категория</span>
                    @elseif($menu->type === 'tag')
                        <span class="badge bg-secondary ms-1">Тег</span>
                    @elseif($menu->type === 'page')
                        <span class="badge bg-success ms-1">Страница</span>
                    @else
                        <span class="badge bg-primary ms-1">Ссылка</span>
                    @endif
                </div>
                <div>
                    @if($menu->is_active)
                        <span class="badge bg-success me-2">Активно</span>
                    @else
                        <span class="badge bg-secondary me-2">Неактивно</span>
                    @endif
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-primary">Редактировать</a>
                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пункт меню?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
            <small class="text-muted d-block mt-1">
                @if($menu->type === 'category' && $menu->category)
                    <code>{{ route('categories.show', $menu->category->slug) }}</code>
                @elseif($menu->type === 'tag' && $menu->tag)
                    <code>{{ route('tags.show', $menu->tag->slug) }}</code>
                @elseif($menu->type === 'page' && $menu->page)
                    <code>{{ route('pages.show', $menu->page->slug) }}</code>
                @else
                    <code>{{ $menu->final_url }}</code>
                @endif
            </small>
        </div>
    </div>
    @if($menu->children->isNotEmpty())
        <ul class="list-group menu-children mt-2">
            @foreach($menu->children as $child)
                @include('admin.menus.menu-item', ['menu' => $child, 'level' => $level + 1])
            @endforeach
        </ul>
    @endif
</li>

