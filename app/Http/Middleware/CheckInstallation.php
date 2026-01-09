<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Пропускаем проверку для роутов установки
        if ($request->is('install*')) {
            return $next($request);
        }

        $installed = file_exists(storage_path('app/installed'));
        
        // Если установка не завершена, перенаправляем на установку
        if (!$installed) {
            return redirect()->route('install.index');
        }
        
        return $next($request);
    }
}
