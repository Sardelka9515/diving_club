<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->get()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'footer_text' => 'nullable|string',
            'maintenance_mode' => 'boolean',
        ]);

        // 使用事務確保所有設定都成功更新或全部回滾
        DB::transaction(function () use ($request) {
            // 網站名稱
            $this->updateSetting('site_name', $request->site_name);
            
            // 網站描述
            $this->updateSetting('site_description', $request->site_description);
            
            // 聯絡郵箱
            $this->updateSetting('contact_email', $request->contact_email);
            
            // 頁腳文字
            $this->updateSetting('footer_text', $request->footer_text);
            
            // 維護模式
            if ($request->has('maintenance_mode')) {
                if ($request->maintenance_mode) {
                    Artisan::call('down');
                } else {
                    Artisan::call('up');
                }
                $this->updateSetting('maintenance_mode', $request->maintenance_mode ? '1' : '0');
            }
        });

        return redirect()->route('admin.settings.index')
            ->with('success', '系統設定已成功更新');
    }

    private function updateSetting($key, $value)
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now()]
        );
    }
    
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        return redirect()->route('admin.settings.index')
            ->with('success', '系統缓存已成功清除');
    }
}