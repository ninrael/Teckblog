<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use Exception;

class InstallController extends Controller
{
    public function index()
    {
        // Проверяем, не установлен ли уже проект
        if (file_exists(storage_path('app/installed'))) {
            return redirect()->route('home');
        }

        return view('install.index');
    }

    public function checkDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            // Временно изменяем конфигурацию БД
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_database,
                'database.connections.mysql.username' => $request->db_username,
                'database.connections.mysql.password' => $request->db_password,
            ]);

            // Пытаемся подключиться
            DB::connection('mysql')->getPdo();

            // Сохраняем данные в сессию
            session([
                'db_config' => [
                    'host' => $request->db_host,
                    'port' => $request->db_port,
                    'database' => $request->db_database,
                    'username' => $request->db_username,
                    'password' => $request->db_password,
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Подключение к базе данных успешно!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка подключения: ' . $e->getMessage()
            ], 422);
        }
    }

    public function install(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|numeric',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8',
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
        ]);

        try {
            // Обновляем .env файл
            $this->updateEnvFile([
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password ?: '',
                'APP_NAME' => $request->app_name,
                'APP_URL' => $request->app_url,
            ]);

            // Обновляем конфигурацию БД
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_database,
                'database.connections.mysql.username' => $request->db_username,
                'database.connections.mysql.password' => $request->db_password,
            ]);

            // Очищаем кеш конфигурации
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Запускаем миграции
            Artisan::call('migrate', ['--force' => true]);
            
            // Убеждаемся, что таблица sessions создана
            try {
                if (!DB::getSchemaBuilder()->hasTable('sessions')) {
                    Artisan::call('migrate', ['--path' => 'database/migrations/2026_01_08_102556_create_sessions_table.php', '--force' => true]);
                }
            } catch (Exception $e) {
                // Игнорируем ошибку, если таблица уже существует
            }
            
            // Генерируем ключ приложения, если его нет
            if (empty(config('app.key'))) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            // Создаем роли
            $adminRole = Role::firstOrCreate(
                ['slug' => 'admin'],
                ['name' => 'Администратор', 'description' => 'Полный доступ к системе']
            );
            Role::firstOrCreate(
                ['slug' => 'editor'],
                ['name' => 'Редактор', 'description' => 'Может создавать и редактировать посты']
            );
            Role::firstOrCreate(
                ['slug' => 'user'],
                ['name' => 'Пользователь', 'description' => 'Обычный пользователь']
            );

            // Создаем администратора
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'email_verified_at' => now(),
                'role_id' => $adminRole->id,
            ]);

            // Создаем базовые категории
            $categories = [
                ['name' => 'Технологии', 'slug' => 'tehnologii', 'description' => 'Статьи о технологиях'],
                ['name' => 'Гаджеты', 'slug' => 'gadzhety', 'description' => 'Обзоры гаджетов'],
                ['name' => 'Программирование', 'slug' => 'programmirovanie', 'description' => 'Статьи о программировании'],
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }

            // Создаем файл-маркер установки
            File::put(storage_path('app/installed'), date('Y-m-d H:i:s'));

            // Очищаем сессию
            session()->forget('db_config');

            return response()->json([
                'success' => true,
                'message' => 'Установка завершена успешно!',
                'redirect' => route('home')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка установки: ' . $e->getMessage()
            ], 422);
        }
    }

    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        
        if (!File::exists($envFile)) {
            if (File::exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), $envFile);
            } else {
                // Создаем базовый .env файл
                File::put($envFile, "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n\nDB_CONNECTION=mysql\n");
            }
        }

        $envContent = File::get($envFile);

        foreach ($data as $key => $value) {
            // Если значение содержит пробелы или специальные символы, заключаем в кавычки
            if (preg_match('/[\s#\$"\'\\\]/', $value)) {
                $escapedValue = '"' . str_replace(['"', '\\'], ['\"', '\\\\'], $value) . '"';
            } else {
                $escapedValue = $value;
            }
            
            // Заменяем или добавляем значение
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$escapedValue}", $envContent);
            } else {
                // Добавляем в конец файла
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        File::put($envFile, $envContent);
    }
}
