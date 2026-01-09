<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
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

        // Назначаем первого пользователя администратором, если у него нет роли
        $firstUser = User::whereNull('role_id')->first();
        if ($firstUser) {
            $firstUser->update(['role_id' => $adminRole->id]);
        }
    }
}
