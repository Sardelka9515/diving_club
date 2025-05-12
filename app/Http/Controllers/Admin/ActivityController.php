<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with('category')->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }
    
    public function create()
    {
        return view('admin.activities.create');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start|before:start_date',
            'max_participants' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'activity_category' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);
        
        // 處理活動類別，如果不存在就創建
        $categoryName = $validatedData['activity_category'];
        $category = ActivityCategory::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => Str::slug($categoryName)]
        );
        
        $validatedData['activity_category_id'] = $category->id;
        $validatedData['is_published'] = $request->has('is_published');
        
        // 將純文字內容轉換成 HTML
        $validatedData['content'] = $this->textToHtml($validatedData['content']);
        
        // 移除 activity_category，因為我們不需要存儲這個欄位
        unset($validatedData['activity_category']);
        
        Activity::create($validatedData);
        
        return redirect()->route('admin.activities.index')->with('success', '活動已成功創建');
    }
    
    public function show(Activity $activity)
    {
        // 載入相關數據
        $activity->load(['category', 'registrations.user']);
        
        return view('admin.activities.show', compact('activity'));
    }
    
    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }
    
    public function update(Request $request, Activity $activity)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date|after:registration_start|before:start_date',
            'max_participants' => 'required|integer|min:0',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'activity_category' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);
        
        // 處理活動類別
        $categoryName = $validatedData['activity_category'];
        $category = ActivityCategory::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => Str::slug($categoryName)]
        );
        
        $validatedData['activity_category_id'] = $category->id;
        $validatedData['is_published'] = $request->has('is_published');
        
        // 將純文字內容轉換成 HTML
        $validatedData['content'] = $this->textToHtml($validatedData['content']);
        
        unset($validatedData['activity_category']);
        
        $activity->update($validatedData);
        
        return redirect()->route('admin.activities.index')->with('success', '活動已成功更新');
    }
    
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', '活動已成功刪除');
    }
    
    /**
     * 將純文字轉換為 HTML
     */
    private function textToHtml($text)
    {
        // 清理可能存在的 HTML 標籤
        $text = strip_tags($text);
        
        // 將連續兩個換行轉為段落分隔符號
        $text = preg_replace("/\n\s*\n/", "\n\n", $text);
        
        // 分割段落
        $paragraphs = explode("\n\n", $text);
        $html = '';
        
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (!empty($paragraph)) {
                // 處理段落內的單個換行符號
                $paragraph = nl2br($paragraph);
                $html .= '<p>' . $paragraph . '</p>';
            }
        }
        
        return $html;
    }
}