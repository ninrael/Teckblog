<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $pages = $query->latest()->paginate(15)->withQueryString();

        // Получаем системные страницы из настроек
        $systemPages = [
            [
                'id' => 'about',
                'title' => \App\Models\Setting::get('about_page_title', 'О нас'),
                'slug' => 'about',
                'url' => route('about.show'),
                'edit_route' => 'admin.about.edit',
                'is_published' => true,
                'created_at' => now(),
                'type' => 'system'
            ],
            [
                'id' => 'terms',
                'title' => \App\Models\Setting::get('terms_page_title', 'Условия использования'),
                'slug' => 'policy/terms',
                'url' => route('policy.terms'),
                'edit_route' => 'admin.policy.editTerms',
                'is_published' => true,
                'created_at' => now(),
                'type' => 'system'
            ],
            [
                'id' => 'privacy',
                'title' => \App\Models\Setting::get('privacy_page_title', 'Политика конфиденциальности'),
                'slug' => 'policy/privacy',
                'url' => route('policy.privacy'),
                'edit_route' => 'admin.policy.editPrivacy',
                'is_published' => true,
                'created_at' => now(),
                'type' => 'system'
            ],
        ];

        return view('admin.pages.index', compact('pages', 'systemPages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Страница успешно создана!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return redirect()->route('pages.show', $page->slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Страница успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Страница успешно удалена!');
    }
}
