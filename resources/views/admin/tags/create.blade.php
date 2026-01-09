@extends('admin.layout')

@section('title', 'Создать тег')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Создать тег</h2>
    <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Назад</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Отмена</a>
                <button type="submit" class="btn btn-primary">Создать</button>
            </div>
        </form>
    </div>
</div>
@endsection

