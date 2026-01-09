<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function show()
    {
        $content = Setting::get('about_page_content', '');
        $title = Setting::get('about_page_title', 'О нас');
        
        return view('about.show', compact('content', 'title'));
    }
}
