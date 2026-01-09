@extends('admin.layout')

@section('title', 'Пользователи')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Управление пользователями</h2>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Статус</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <select name="role_id" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                        <option value="">Без роли</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge bg-danger">Забанен</span>
                                    @if($user->ban_reason)
                                        <br><small class="text-muted">{{ $user->ban_reason }}</small>
                                    @endif
                                @else
                                    <span class="badge bg-success">Активен</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($user->is_banned)
                                        <form action="{{ route('admin.users.unban', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Разбанить</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Забанить</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#banReasonModal{{ $user->id }}">
                                        Причина
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить пользователя?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                                    </form>
                                </div>

                                <!-- Modal для причины бана -->
                                <div class="modal fade" id="banReasonModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.users.ban-reason', $user) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Причина бана: {{ $user->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="ban_reason" class="form-label">Причина бана</label>
                                                        <textarea class="form-control" id="ban_reason" name="ban_reason" rows="3">{{ $user->ban_reason }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

