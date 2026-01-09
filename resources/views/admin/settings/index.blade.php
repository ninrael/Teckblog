@extends('admin.layout')

@section('title', 'Настройки')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Настройки блога</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="blog_name" class="form-label">Название блога</label>
                    <input type="text" class="form-control" id="blog_name" name="settings[blog_name]" value="{{ \App\Models\Setting::get('blog_name', config('app.name')) }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="blog_description" class="form-label">Описание блога</label>
                    <input type="text" class="form-control" id="blog_description" name="settings[blog_description]" value="{{ \App\Models\Setting::get('blog_description') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="blog_email" class="form-label">Email администратора</label>
                    <input type="email" class="form-control" id="blog_email" name="settings[blog_email]" value="{{ \App\Models\Setting::get('blog_email') }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="posts_per_page" class="form-label">Постов на странице</label>
                    <input type="number" class="form-control" id="posts_per_page" name="settings[posts_per_page]" value="{{ \App\Models\Setting::get('posts_per_page', 12) }}" min="1" max="50">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="comments_moderation" class="form-label">Модерация комментариев</label>
                    <select class="form-select" id="comments_moderation" name="settings[comments_moderation]">
                        <option value="1" {{ \App\Models\Setting::get('comments_moderation', true) ? 'selected' : '' }}>Включена</option>
                        <option value="0" {{ !\App\Models\Setting::get('comments_moderation', true) ? 'selected' : '' }}>Отключена</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="registration_enabled" class="form-label">Регистрация пользователей</label>
                    <select class="form-select" id="registration_enabled" name="settings[registration_enabled]">
                        <option value="1" {{ \App\Models\Setting::get('registration_enabled', true) ? 'selected' : '' }}>Включена</option>
                        <option value="0" {{ !\App\Models\Setting::get('registration_enabled', true) ? 'selected' : '' }}>Отключена</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sidebar_position" class="form-label">Расположение сайдбара</label>
                    <select class="form-select" id="sidebar_position" name="settings[sidebar_position]">
                        <option value="right" {{ \App\Models\Setting::get('sidebar_position', 'right') === 'right' ? 'selected' : '' }}>Справа (по умолчанию)</option>
                        <option value="left" {{ \App\Models\Setting::get('sidebar_position', 'right') === 'left' ? 'selected' : '' }}>Слева</option>
                    </select>
                    <small class="form-text text-muted">Позиция сайдбара с категориями и тегами</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sidebar_on_post" class="form-label">Сайдбар на странице поста</label>
                    <select class="form-select" id="sidebar_on_post" name="settings[sidebar_on_post]">
                        <option value="1" {{ \App\Models\Setting::get('sidebar_on_post', true) ? 'selected' : '' }}>Показывать</option>
                        <option value="0" {{ !\App\Models\Setting::get('sidebar_on_post', true) ? 'selected' : '' }}>Скрывать</option>
                    </select>
                    <small class="form-text text-muted">Отображать ли сайдбар с категориями и тегами на странице отдельного поста</small>
                </div>

                <div class="col-12 mb-3">
                    <label for="footer_text" class="form-label">Текст в футере</label>
                    <textarea class="form-control" id="footer_text" name="settings[footer_text]" rows="3">{{ \App\Models\Setting::get('footer_text') }}</textarea>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Сохранить настройки</button>
            </div>
        </form>
    </div>
</div>
@endsection

