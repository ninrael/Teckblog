<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Page $page)
    {
        if (!$page->is_published && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }
}
