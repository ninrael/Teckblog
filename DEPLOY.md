# Инструкция по развертыванию проекта

## Требования
- PHP 8.3 или выше
- Composer
- MySQL 5.7+ или MariaDB 10.3+
- Node.js и npm (для сборки фронтенда)

## Шаги развертывания

1. **Загрузите файлы на сервер**
   - Распакуйте архив в корневую директорию веб-сервера

2. **Установите зависимости**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   ```

3. **Настройте файл .env**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Отредактируйте .env файл с вашими настройками базы данных и других сервисов.

4. **Настройте базу данных**
   - Создайте базу данных MySQL
   - Обновите настройки в .env файле:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

5. **Выполните миграции**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=RoleSeeder
   ```

6. **Настройте права доступа**
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

7. **Создайте символическую ссылку для storage**
   ```bash
   php artisan storage:link
   ```

8. **Очистите и оптимизируйте кэш**
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   php artisan cache:clear
   ```

9. **Настройте веб-сервер**
   - Убедитесь, что корневая директория веб-сервера указывает на папку `public`
   - Для Apache: используйте .htaccess из папки public
   - Для Nginx: настройте конфигурацию для Laravel

10. **Проверьте настройки**
    - Убедитесь, что APP_DEBUG=false в production
    - Проверьте права доступа к папкам storage и bootstrap/cache

## Важные замечания
- Не загружайте файл .env на сервер - создайте его вручную из .env.example
- Убедитесь, что папка storage доступна для записи
- Проверьте настройки базы данных перед выполнением миграций

