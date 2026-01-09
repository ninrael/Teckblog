@extends('admin.layout')

@section('title', 'Меню')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление меню</h2>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Создать пункт меню</a>
</div>

<div class="card">
    <div class="card-body">
        <div id="menu-list">
            @if($menus->isNotEmpty())
                <ul class="list-group" id="sortable-menu">
                    @foreach($menus as $menu)
                        @include('admin.menus.menu-item', ['menu' => $menu, 'level' => 0])
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">
                    Пунктов меню пока нет. <a href="{{ route('admin.menus.create') }}">Создайте первый пункт меню</a>.
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .menu-item {
        cursor: move;
        user-select: none;
    }
    .menu-item:hover {
        background-color: #f8f9fa;
    }
    .menu-item.dragging {
        opacity: 0.5;
    }
    .menu-children {
        margin-left: 30px;
        margin-top: 5px;
    }
    .menu-handle {
        cursor: move;
        color: #6c757d;
        margin-right: 10px;
    }
    .menu-handle:hover {
        color: #495057;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuList = document.getElementById('sortable-menu');
    if (!menuList) return;

    // Создаем Sortable для корневых элементов
    const rootSortable = new Sortable(menuList, {
        handle: '.menu-handle',
        animation: 150,
        group: 'nested',
        fallbackOnBody: true,
        swapThreshold: 0.65,
        onEnd: function(evt) {
            updateMenuOrder();
        }
    });

    // Создаем Sortable для вложенных меню
    document.querySelectorAll('.menu-children').forEach(function(childrenList) {
        new Sortable(childrenList, {
            handle: '.menu-handle',
            animation: 150,
            group: 'nested',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd: function(evt) {
                updateMenuOrder();
            }
        });
    });

    function updateMenuOrder() {
        const items = [];
        let order = 0;

        function processItems(list, parentId = null) {
            Array.from(list.children).forEach(function(item) {
                if (item.classList.contains('menu-item')) {
                    const menuId = item.dataset.menuId;
                    items.push({
                        id: menuId,
                        order: order++,
                        parent_id: parentId
                    });

                    // Обрабатываем вложенные элементы
                    const childrenList = item.querySelector('.menu-children');
                    if (childrenList) {
                        processItems(childrenList, menuId);
                    }
                }
            });
        }

        processItems(menuList);

        // Отправляем данные на сервер
        fetch('{{ route("admin.menus.update-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ menus: items })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Можно показать уведомление об успехе
                console.log('Порядок меню обновлен');
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
    }
});
</script>
@endsection
