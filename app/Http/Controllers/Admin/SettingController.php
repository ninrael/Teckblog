<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('key')->get();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $type = 'text';
            if (is_numeric($value)) {
                $type = 'number';
            } elseif (in_array(strtolower($value), ['1', '0', 'true', 'false', 'on', 'off'])) {
                $type = 'boolean';
                $value = in_array(strtolower($value), ['1', 'true', 'on']) ? '1' : '0';
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'type' => $type,
                ]
            );
        }

        return back()->with('success', 'Настройки обновлены!');
    }
}
