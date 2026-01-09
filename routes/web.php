<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostAdminController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\TagAdminController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CommentAdminController;
use App\Http\Controllers\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\AboutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Роуты установки (без middleware)
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'index'])->name('index');
    Route::post('/check-db', [InstallController::class, 'checkDatabase'])->name('check-db');
    Route::post('/install', [InstallController::class, 'install'])->name('install');
});

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tags/{tag:slug}', [CategoryController::class, 'showTag'])->name('tags.show');

// Политика и правила
Route::get('/policy/terms', [PolicyController::class, 'show'])->name('policy.terms');
Route::get('/policy/privacy', [PolicyController::class, 'privacy'])->name('policy.privacy');

// Страница "О нас"
Route::get('/about', [AboutController::class, 'show'])->name('about.show');

// Публичные страницы
Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Роуты аутентификации
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    // Профиль
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    
    // Лайки
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    
    // Загрузка изображений для редактора
    Route::post('/upload-image', [ImageUploadController::class, 'upload'])->name('upload.image');
});

// Публичные роуты для постов (должны быть после защищенных, чтобы избежать конфликтов)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Админ-панель
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Управление пользователями
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
    Route::post('/users/{user}/ban-reason', [UserController::class, 'updateBanReason'])->name('users.ban-reason');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Управление постами
    Route::get('/posts', [PostAdminController::class, 'index'])->name('posts.index');
    Route::get('/posts/{post}/edit', [PostAdminController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostAdminController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/publish', [PostAdminController::class, 'publish'])->name('posts.publish');
    Route::post('/posts/{post}/unpublish', [PostAdminController::class, 'unpublish'])->name('posts.unpublish');
    Route::delete('/posts/{post}', [PostAdminController::class, 'destroy'])->name('posts.destroy');
    
    // Управление категориями
    Route::resource('categories', CategoryAdminController::class)->except(['show']);
    
    // Управление тегами
    Route::resource('tags', TagAdminController::class)->except(['show']);
    
    // Управление меню
    Route::resource('menus', MenuController::class);
    Route::post('/menus/update-order', [MenuController::class, 'updateOrder'])->name('menus.update-order');
    
    // Управление комментариями
    Route::get('/comments', [CommentAdminController::class, 'index'])->name('comments.index');
    Route::get('/comments/{comment}/edit', [CommentAdminController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentAdminController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentAdminController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/approve', [CommentAdminController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{comment}/reject', [CommentAdminController::class, 'reject'])->name('comments.reject');
    
    // Настройки
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Редактирование страницы "О нас"
    Route::get('/about', [AdminAboutController::class, 'edit'])->name('about.edit');
    Route::put('/about', [AdminAboutController::class, 'update'])->name('about.update');
    
    // Редактирование политик
    Route::get('/policy/terms', [AdminAboutController::class, 'editTerms'])->name('policy.editTerms');
    Route::put('/policy/terms', [AdminAboutController::class, 'updateTerms'])->name('policy.updateTerms');
    Route::get('/policy/privacy', [AdminAboutController::class, 'editPrivacy'])->name('policy.editPrivacy');
    Route::put('/policy/privacy', [AdminAboutController::class, 'updatePrivacy'])->name('policy.updatePrivacy');
    
    // Управление страницами
    Route::resource('pages', AdminPageController::class)->except(['show']);
});
