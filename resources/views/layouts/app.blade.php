<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name')) - Технологический блог</title>

    @stack('styles')

    <!-- Bootstrap 5 CSS -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    @else
        <!-- Fallback to CDN if Vite is not built -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @endif
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f8f9fa;
            padding-top: 90px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
        }
        .footer {
            flex-shrink: 0;
            margin-top: auto;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white;
            width: 100%;
        }
        .footer .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .navbar-wrapper {
            position: fixed;
            top: 15px;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 15px;
        }
        .navbar-island {
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 0.4rem 1rem;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            max-width: 1400px;
            margin: 0 auto;
        }
        @media (max-width: 991px) {
            .navbar-island {
                border-radius: 25px;
                padding: 0.35rem 0.8rem;
            }
        }
        .navbar-brand {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .navbar-nav .nav-link {
            font-size: 0.9rem;
            padding: 0.3rem 0.6rem;
        }
        .search-form {
            display: flex;
            align-items: center;
        }
        .search-input {
            border-radius: 20px;
            border: none;
            padding: 0.35rem 1rem;
            font-size: 0.85rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            width: 200px;
            transition: all 0.3s ease;
        }
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .search-input:focus {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
            width: 250px;
        }
        .search-btn {
            border-radius: 20px;
            border: none;
            padding: 0.35rem 0.8rem;
            margin-left: 0.5rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .search-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }
        .search-btn svg {
            width: 16px;
            height: 16px;
        }
        .dropdown-menu {
            right: 0;
            left: auto !important;
            transform: translateX(-20px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border: none;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 400;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-outline-danger {
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            transition: all 0.3s ease;
        }
        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            transform: scale(1.1);
        }
        .btn-outline-danger svg {
            width: 14px;
            height: 14px;
        }
        .profile-icon {
            fill: #2c3e50;
        }
        .btn-outline-primary {
            border: 1px solid #2c3e50;
            color: #2c3e50;
            border-radius: 20px;
            padding: 0.3rem 0.8rem;
            font-size: 0.85rem;
            font-weight: 400;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            border-color: #2c3e50;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .auth-link {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .auth-link:hover {
            color: #34495e;
            text-decoration: underline;
        }
        .breadcrumb a {
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .breadcrumb a:hover {
            color: #34495e;
            text-decoration: underline;
        }
        .badge {
            font-weight: normal !important;
        }
        .badge.bg-primary {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white;
        }
        .list-unstyled a {
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .list-unstyled a:hover {
            color: #34495e;
            text-decoration: underline;
        }
        .card-title a {
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .card-title a:hover {
            color: #34495e;
            text-decoration: underline;
        }
        /* Стили для ссылок в блоке "Топ статьи" */
        .card-body h6 a.text-decoration-none {
            color: #2c3e50 !important;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .card-body h6 a.text-decoration-none:hover {
            color: #34495e !important;
            text-decoration: underline;
        }
        @media (max-width: 991px) {
            .search-input {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .search-form {
                flex-direction: column;
                width: 100%;
            }
            .dropdown-menu {
                transform: translateX(0);
            }
        }
        /* Стили пагинации */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
        }
        .pagination .page-item {
            margin: 0;
        }
        .pagination .page-link {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }
        /* Скрываем текст "Showing X to Y of Z results" */
        .pagination ~ .small.text-muted,
        nav .small.text-muted {
            display: none !important;
        }
        .pagination .page-link:hover {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: white;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(127, 140, 141, 0.4);
            transform: scale(1.1);
        }
        .pagination .page-item.disabled .page-link {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.5;
        }
        .pagination .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="navbar-wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark navbar-island">
            <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">TechBlog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    </li>
                    @php
                        $menus = \App\Models\Menu::where('is_active', true)->orderBy('order')->get();
                    @endphp
                    @foreach($menus as $menu)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $menu->final_url }}" target="{{ $menu->target }}">
                                {{ $menu->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <form action="{{ route('posts.index') }}" method="GET" class="search-form me-3">
                    <input type="text" name="search" class="search-input" placeholder="Поиск..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </form>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                    </svg>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                        </svg>
                                        Профиль
                                    </a>
                                </li>
                                @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.292-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.292c.415.764-.42 1.6-1.185 1.184l-.292-.159a1.873 1.873 0 0 0-2.692 1.116l-.094.319c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.693-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.292A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                            </svg>
                                            Админ-панель
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('posts.create') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                        </svg>
                                        Создать пост
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    @if (Route::has('logout'))
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Выйти</button>
                                        </form>
                                    @endif
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="guestDropdown" role="button" data-bs-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v1zm-5 8a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-.5.5h-8z"/>
                                            <path d="M4.5 1.5A1.5 1.5 0 0 0 3 3v10a1.5 1.5 0 0 0 1.5 1.5h8a1.5 1.5 0 0 0 1.5-1.5V8a.5.5 0 0 1 1 0v5a2.5 2.5 0 0 1-2.5 2.5h-8A2.5 2.5 0 0 1 1 13V3a2.5 2.5 0 0 1 2.5-2.5h8A2.5 2.5 0 0 1 14 3v5a.5.5 0 0 1-1 0V3a1.5 1.5 0 0 0-1.5-1.5h-8z"/>
                                        </svg>
                                        Войти
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('register') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                            <path fill-rule="evenodd" d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zm.5-9.5a.5.5 0 0 0-1 0v2h-2a.5.5 0 0 0 0 1h2v2a.5.5 0 0 0 1 0v-2h2a.5.5 0 0 0 0-1h-2v-2z"/>
                                        </svg>
                                        Регистрация
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>
    </div>

    <main class="container my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">
                <a href="{{ route('about.show') }}" class="text-white text-decoration-none me-3">О нас</a>
                <a href="{{ route('policy.terms') }}" class="text-white text-decoration-none me-3">Условия использования</a>
                <a href="{{ route('policy.privacy') }}" class="text-white text-decoration-none">Политика конфиденциальности</a>
            </p>
            <p class="mb-0 mt-2">&copy; {{ date('Y') }} TechBlog. Все права защищены.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

