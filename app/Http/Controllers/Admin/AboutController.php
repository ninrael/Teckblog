<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $title = Setting::get('about_page_title', 'О нас');
        $content = Setting::get('about_page_content', null);
        
        // Если контент пустой или null, используем дефолтное значение
        if (empty($content) || $content === null) {
            $content = '<p>Добро пожаловать на наш технологический блог!</p><p>Здесь вы найдете последние новости о технологиях, обзоры гаджетов и полезные статьи о программировании.</p>';
        }
        
        return view('admin.about.edit', compact('title', 'content'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Setting::set('about_page_title', $validated['title'], 'text');
        Setting::set('about_page_content', $validated['content'], 'text');

        return redirect()->route('admin.about.edit')
            ->with('success', 'Страница "О нас" успешно обновлена!');
    }

    public function editTerms()
    {
        $title = Setting::get('terms_page_title', 'Условия использования');
        $content = Setting::get('terms_page_content', null);
        
        // Если контент пустой или null, используем дефолтное значение из статического view
        if (empty($content) || $content === null) {
            $content = $this->getDefaultTermsContent();
        }
        
        return view('admin.policy.edit', [
            'type' => 'terms',
            'title' => $title,
            'content' => $content,
            'route' => 'admin.policy.updateTerms',
            'viewRoute' => 'policy.terms'
        ]);
    }

    public function updateTerms(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Setting::set('terms_page_title', $validated['title'], 'text');
        Setting::set('terms_page_content', $validated['content'], 'text');

        return redirect()->route('admin.policy.editTerms')
            ->with('success', 'Страница "Условия использования" успешно обновлена!');
    }

    public function editPrivacy()
    {
        $title = Setting::get('privacy_page_title', 'Политика конфиденциальности');
        $content = Setting::get('privacy_page_content', null);
        
        // Если контент пустой или null, используем дефолтное значение из статического view
        if (empty($content) || $content === null) {
            $content = $this->getDefaultPrivacyContent();
        }
        
        return view('admin.policy.edit', [
            'type' => 'privacy',
            'title' => $title,
            'content' => $content,
            'route' => 'admin.policy.updatePrivacy',
            'viewRoute' => 'policy.privacy'
        ]);
    }

    private function getDefaultTermsContent(): string
    {
        return '<div class="mb-4">
            <h4>1. Общие положения</h4>
            <p>Добро пожаловать на наш технологический блог. Используя наш сайт, вы соглашаетесь соблюдать следующие правила и условия использования.</p>
        </div>

        <div class="mb-4">
            <h4>2. Регистрация и учетные записи</h4>
            <p>При регистрации вы обязуетесь предоставлять достоверную информацию. Вы несете ответственность за безопасность вашей учетной записи и пароля.</p>
        </div>

        <div class="mb-4">
            <h4>3. Контент пользователей</h4>
            <p>Публикуя контент на нашем сайте, вы гарантируете, что:</p>
            <ul>
                <li>Контент не нарушает права третьих лиц</li>
                <li>Контент не содержит оскорбительных или незаконных материалов</li>
                <li>Вы имеете право публиковать данный контент</li>
            </ul>
        </div>

        <div class="mb-4">
            <h4>4. Комментарии</h4>
            <p>Комментарии должны быть уважительными и по теме. Запрещены:</p>
            <ul>
                <li>Оскорбления и угрозы</li>
                <li>Спам и реклама</li>
                <li>Распространение ложной информации</li>
            </ul>
        </div>

        <div class="mb-4">
            <h4>5. Интеллектуальная собственность</h4>
            <p>Весь контент сайта защищен авторским правом. Использование материалов без разрешения запрещено.</p>
        </div>

        <div class="mb-4">
            <h4>6. Ответственность</h4>
            <p>Администрация сайта не несет ответственности за контент, размещенный пользователями. Мы оставляем за собой право удалять любой контент без предупреждения.</p>
        </div>

        <div class="mb-4">
            <h4>7. Изменения в правилах</h4>
            <p>Мы оставляем за собой право изменять эти правила в любое время. Продолжение использования сайта после изменений означает ваше согласие с новыми правилами.</p>
        </div>';
    }

    private function getDefaultPrivacyContent(): string
    {
        return '<div class="mb-4">
            <h4>1. Сбор информации</h4>
            <p>Мы собираем следующую информацию:</p>
            <ul>
                <li>Имя и email при регистрации</li>
                <li>IP-адрес и данные браузера</li>
                <li>Информация о взаимодействии с сайтом</li>
            </ul>
        </div>

        <div class="mb-4">
            <h4>2. Использование информации</h4>
            <p>Собранная информация используется для:</p>
            <ul>
                <li>Предоставления услуг сайта</li>
                <li>Улучшения пользовательского опыта</li>
                <li>Обеспечения безопасности</li>
                <li>Связи с пользователями</li>
            </ul>
        </div>

        <div class="mb-4">
            <h4>3. Защита данных</h4>
            <p>Мы принимаем меры для защиты ваших персональных данных от несанкционированного доступа, изменения, раскрытия или уничтожения.</p>
        </div>

        <div class="mb-4">
            <h4>4. Cookies</h4>
            <p>Наш сайт использует cookies для улучшения работы и персонализации контента. Вы можете отключить cookies в настройках браузера.</p>
        </div>

        <div class="mb-4">
            <h4>5. Передача данных третьим лицам</h4>
            <p>Мы не продаем и не передаем ваши персональные данные третьим лицам, за исключением случаев, предусмотренных законодательством.</p>
        </div>

        <div class="mb-4">
            <h4>6. Ваши права</h4>
            <p>Вы имеете право:</p>
            <ul>
                <li>Получить доступ к своим персональным данным</li>
                <li>Исправить неточные данные</li>
                <li>Удалить свои данные</li>
                <li>Отозвать согласие на обработку данных</li>
            </ul>
        </div>

        <div class="mb-4">
            <h4>7. Изменения в политике</h4>
            <p>Мы можем обновлять эту политику конфиденциальности. О существенных изменениях мы уведомим пользователей.</p>
        </div>

        <div class="mb-4">
            <h4>8. Контакты</h4>
            <p>По вопросам конфиденциальности обращайтесь: ' . (Setting::get('blog_email') ?: config('mail.from.address', 'admin@example.com')) . '</p>
        </div>';
    }

    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Setting::set('privacy_page_title', $validated['title'], 'text');
        Setting::set('privacy_page_content', $validated['content'], 'text');

        return redirect()->route('admin.policy.editPrivacy')
            ->with('success', 'Страница "Политика конфиденциальности" успешно обновлена!');
    }
}
