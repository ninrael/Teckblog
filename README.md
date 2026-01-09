# TechBlog - Технологический и гаджет блог

Технологический блог на Laravel 11 с использованием Blade, Bootstrap 5, Laravel Breeze и Laravel Sanctum.

## Технологический стек

- **Framework**: Laravel 12 (совместим с Laravel 11)
- **Frontend**: Blade + Bootstrap 5
- **Auth**: Laravel Breeze (Blade version)
- **API**: Laravel Sanctum (токены)
- **Database**: MySQL
- **Деплой**: Laravel Forge / VPS / Render

## Установка

### Требования

- PHP >= 8.2
- Composer
- Node.js и npm
- MySQL

### Шаги установки

1. **Клонируйте репозиторий или перейдите в папку проекта**
   ```bash
   cd C:\laragon\www\blog
   ```

2. **Установите зависимости Composer**
   ```bash
   composer install
   ```

3. **Установите зависимости npm**
   ```bash
   npm install
   ```

4. **Настройте файл .env**
   - Скопируйте `.env.example` в `.env` (если еще не сделано)
   - Настройте подключение к базе данных MySQL:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=blog
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Создайте базу данных**
   ```sql
   CREATE DATABASE blog;
   ```

6. **Сгенерируйте ключ приложения**
   ```bash
   php artisan key:generate
   ```

7. **Запустите миграции**
   ```bash
   php artisan migrate
   ```

8. **Соберите фронтенд ресурсы**
   ```bash
   npm run build
   ```

9. **Запустите сервер разработки**
   ```bash
   php artisan serve
   ```

   Или используйте встроенный сервер Laragon.

## Установка Laravel Breeze

Если Breeze еще не установлен:

```bash
php artisan breeze:install blade
```

Затем выполните миграции:
```bash
php artisan migrate
```

## Структура проекта

### Модели

- **Post** - Посты блога
- **Category** - Категории постов
- **Tag** - Теги для постов
- **Comment** - Комментарии к постам
- **User** - Пользователи (расширена для работы с Sanctum)

### Контроллеры

- **PostController** - Управление постами (CRUD)
- **CategoryController** - Просмотр категорий
- **CommentController** - Управление комментариями
- **Api\PostController** - API для постов (с Sanctum)

### Роуты

- **Web роуты**: `/routes/web.php`
- **API роуты**: `/routes/api.php`

### Views

- **layouts/app.blade.php** - Основной layout с Bootstrap 5
- **posts/** - Views для постов (index, show, create, edit)
- **categories/** - Views для категорий
- **tags/** - Views для тегов

## API Endpoints

### Публичные

- `GET /api/posts` - Список опубликованных постов
- `GET /api/posts/{id}` - Получить пост по ID

### Защищенные (требуют токен Sanctum)

- `POST /api/posts` - Создать пост
- `PUT /api/posts/{id}` - Обновить пост
- `DELETE /api/posts/{id}` - Удалить пост
- `GET /api/user` - Получить текущего пользователя

### Получение токена

Для получения токена Sanctum, создайте эндпоинт для аутентификации или используйте стандартные методы Laravel Breeze.

## Функциональность

- ✅ Создание, редактирование и удаление постов
- ✅ Категории и теги
- ✅ Комментарии к постам
- ✅ Система авторизации (Laravel Breeze)
- ✅ API с токенами (Laravel Sanctum)
- ✅ Загрузка изображений для постов
- ✅ Счетчик просмотров
- ✅ Пагинация
- ✅ Адаптивный дизайн (Bootstrap 5)

## Деплой

### Laravel Forge

1. Подключите репозиторий к Forge
2. Настройте окружение
3. Укажите команды деплоя:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   npm ci
   npm run build
   ```

### VPS / Render

1. Установите зависимости
2. Настройте `.env` для production
3. Запустите миграции
4. Соберите фронтенд ресурсы
5. Настройте веб-сервер (Nginx/Apache)

## Лицензия

MIT License
