<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $parentMenus = Menu::whereNull('parent_id')->orderBy('order')->get();
        
        // Получаем обычные страницы
        $regularPages = Page::orderBy('title')->get();
        
        // Получаем системные страницы
        $systemPages = collect([
            (object)[
                'id' => 'system_about',
                'title' => \App\Models\Setting::get('about_page_title', 'О нас'),
                'slug' => 'about',
                'is_system' => true,
            ],
            (object)[
                'id' => 'system_terms',
                'title' => \App\Models\Setting::get('terms_page_title', 'Условия использования'),
                'slug' => 'policy/terms',
                'is_system' => true,
            ],
            (object)[
                'id' => 'system_privacy',
                'title' => \App\Models\Setting::get('privacy_page_title', 'Политика конфиденциальности'),
                'slug' => 'policy/privacy',
                'is_system' => true,
            ],
        ]);
        
        // Объединяем обычные и системные страницы
        $pages = $regularPages->concat($systemPages);
        
        return view('admin.menus.create', compact('categories', 'tags', 'pages', 'parentMenus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:custom,category,tag,page',
            'url' => 'required_if:type,custom|nullable|url',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'tag_id' => 'required_if:type,tag|nullable|exists:tags,id',
            'page_id' => 'required_if:type,page|nullable|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'target' => 'nullable|in:_self,_blank',
        ]);

        if ($validated['type'] === 'category') {
            $validated['url'] = '#';
        } elseif ($validated['type'] === 'tag') {
            $validated['url'] = '#';
        } elseif ($validated['type'] === 'page') {
            // Обработка системных страниц
            if (str_starts_with($validated['page_id'], 'system_')) {
                $systemPageSlug = match($validated['page_id']) {
                    'system_about' => 'about',
                    'system_terms' => 'policy/terms',
                    'system_privacy' => 'policy/privacy',
                    default => 'about',
                };
                $validated['url'] = match($systemPageSlug) {
                    'about' => route('about.show'),
                    'policy/terms' => route('policy.terms'),
                    'policy/privacy' => route('policy.privacy'),
                    default => route('about.show'),
                };
                $validated['page_id'] = null; // Системные страницы не имеют ID в таблице pages
            } else {
                // Обычная страница
                $validated['url'] = '#';
                // Проверяем существование страницы
                if (!Page::where('id', $validated['page_id'])->exists()) {
                    return back()->withErrors(['page_id' => 'Выбранная страница не найдена.'])->withInput();
                }
            }
        }

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Пункт меню успешно создан!');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $parentMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('order')->get();
        
        // Получаем обычные страницы
        $regularPages = Page::orderBy('title')->get();
        
        // Получаем системные страницы
        $systemPages = collect([
            (object)[
                'id' => 'system_about',
                'title' => \App\Models\Setting::get('about_page_title', 'О нас'),
                'slug' => 'about',
                'is_system' => true,
            ],
            (object)[
                'id' => 'system_terms',
                'title' => \App\Models\Setting::get('terms_page_title', 'Условия использования'),
                'slug' => 'policy/terms',
                'is_system' => true,
            ],
            (object)[
                'id' => 'system_privacy',
                'title' => \App\Models\Setting::get('privacy_page_title', 'Политика конфиденциальности'),
                'slug' => 'policy/privacy',
                'is_system' => true,
            ],
        ]);
        
        // Объединяем обычные и системные страницы
        $pages = $regularPages->concat($systemPages);
        
        return view('admin.menus.edit', compact('menu', 'categories', 'tags', 'pages', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:custom,category,tag,page',
            'url' => 'required_if:type,custom|nullable|url',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'tag_id' => 'required_if:type,tag|nullable|exists:tags,id',
            'page_id' => 'required_if:type,page|nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'target' => 'nullable|in:_self,_blank',
        ]);
        
        // Предотвращаем создание циклических ссылок
        if (isset($validated['parent_id']) && $validated['parent_id'] == $menu->id) {
            return back()->withErrors(['parent_id' => 'Пункт меню не может быть родителем самого себя.'])->withInput();
        }

        if ($validated['type'] === 'category') {
            $validated['url'] = '#';
            $validated['tag_id'] = null;
            $validated['page_id'] = null;
        } elseif ($validated['type'] === 'tag') {
            $validated['url'] = '#';
            $validated['category_id'] = null;
            $validated['page_id'] = null;
        } elseif ($validated['type'] === 'page') {
            // Обработка системных страниц
            if (str_starts_with($validated['page_id'], 'system_')) {
                $systemPageSlug = match($validated['page_id']) {
                    'system_about' => 'about',
                    'system_terms' => 'policy/terms',
                    'system_privacy' => 'policy/privacy',
                    default => 'about',
                };
                $validated['url'] = match($systemPageSlug) {
                    'about' => route('about.show'),
                    'policy/terms' => route('policy.terms'),
                    'policy/privacy' => route('policy.privacy'),
                    default => route('about.show'),
                };
                $validated['page_id'] = null; // Системные страницы не имеют ID в таблице pages
            } else {
                // Обычная страница
                $validated['url'] = '#';
                // Проверяем существование страницы
                if (!Page::where('id', $validated['page_id'])->exists()) {
                    return back()->withErrors(['page_id' => 'Выбранная страница не найдена.'])->withInput();
                }
            }
            $validated['category_id'] = null;
            $validated['tag_id'] = null;
        } else {
            $validated['category_id'] = null;
            $validated['tag_id'] = null;
            $validated['page_id'] = null;
        }

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Пункт меню успешно обновлен!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'Пункт меню удален!');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.order' => 'required|integer',
            'menus.*.parent_id' => 'nullable|exists:menus,id',
        ]);

        foreach ($request->menus as $item) {
            Menu::where('id', $item['id'])->update([
                'order' => $item['order'],
                'parent_id' => $item['parent_id'] ?? null,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Порядок меню обновлен!']);
    }
}
