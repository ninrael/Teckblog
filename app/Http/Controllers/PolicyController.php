<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function show()
    {
        $content = Setting::get('terms_page_content', '');
        $title = Setting::get('terms_page_title', 'Условия использования');
        
        if (empty($content)) {
            // Если контент не задан, используем статический view
            return view('policy.terms');
        }
        
        return view('policy.show', [
            'title' => $title,
            'content' => $content
        ]);
    }

    public function privacy()
    {
        $content = Setting::get('privacy_page_content', '');
        $title = Setting::get('privacy_page_title', 'Политика конфиденциальности');
        
        if (empty($content)) {
            // Если контент не задан, используем статический view
            return view('policy.privacy');
        }
        
        return view('policy.show', [
            'title' => $title,
            'content' => $content
        ]);
    }
}
