# Инструкция по настройке TechBlog

## Быстрый старт

### 1. Установите зависимости

```bash
# Composer зависимости
composer install

# NPM зависимости
npm install
```

### 2. Настройте базу данных

Убедитесь, что в файле `.env` настроено подключение к MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

Создайте базу данных в MySQL:
```sql
CREATE DATABASE blog;
```

### 3. Запустите миграции

```bash
php artisan migrate
```

### 4. Установите Laravel Breeze (если еще не установлен)

```bash
php artisan breeze:install blade
php artisan migrate
```

### 5. Соберите фронтенд

```bash
npm run build
```

Или для разработки:
```bash
npm run dev
```

### 6. Запустите сервер

```bash
php artisan serve
```

Или используйте встроенный сервер Laragon.

## Создание тестовых данных

После установки вы можете создать тестовые категории, теги и посты через:

1. **Tinker**:
```bash
php artisan tinker
```

2. **Или создайте Seeder**:
```bash
php artisan make:seeder BlogSeeder
```

## API Использование

### Получение токена

Для работы с API необходимо получить токен Sanctum. Создайте эндпоинт для аутентификации или используйте стандартные методы Laravel Breeze.

Пример получения токена:
```php
$user = User::find(1);
$token = $user->createToken('api-token')->plainTextToken;
```

### Использование API

```bash
# Получить список постов
GET /api/posts

# Получить пост
GET /api/posts/{id}

# Создать пост (требует токен)
POST /api/posts
Authorization: Bearer {token}

# Обновить пост (требует токен)
PUT /api/posts/{id}
Authorization: Bearer {token}

# Удалить пост (требует токен)
DELETE /api/posts/{id}
Authorization: Bearer {token}
```

## Структура базы данных

- **users** - Пользователи
- **categories** - Категории постов
- **tags** - Теги
- **posts** - Посты блога
- **comments** - Комментарии
- **post_tag** - Связь многие-ко-многим между постами и тегами

## Полезные команды

```bash
# Очистить кеш
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Оптимизация
php artisan optimize

# Создать симлинк для storage
php artisan storage:link
```

## Деплой

Для production окружения:

1. Установите зависимости без dev:
```bash
composer install --no-dev --optimize-autoloader
```

2. Соберите фронтенд:
```bash
npm ci
npm run build
```

3. Запустите миграции:
```bash
php artisan migrate --force
```

4. Создайте симлинк для storage:
```bash
php artisan storage:link
```

5. Настройте права доступа:
```bash
chmod -R 775 storage bootstrap/cache
```

