<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }
    
    public function create()
    {
        return view('admin.announcements.create');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        // 將純文字轉換為 HTML 段落
        $validatedData['content'] = $this->formatTextToHtml($validatedData['content']);
        
        $validatedData['is_pinned'] = $request->has('is_pinned');
        $validatedData['is_published'] = $request->has('is_published');
        $validatedData['user_id'] = auth()->id();
        $validatedData['published_at'] = $request->has('is_published') ? now() : null;
        
        Announcement::create($validatedData);
        
        return redirect()->route('admin.announcements.index')->with('success', '公告已成功創建');
    }
    
    public function show(Announcement $announcement)
    {
        $announcement->load('user');
        return view('admin.announcements.show', compact('announcement'));
    }
    
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }
    
    public function update(Request $request, Announcement $announcement)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_pinned' => 'boolean',
            'is_published' => 'boolean',
        ]);
        
        // 將純文字轉換為 HTML 段落
        $validatedData['content'] = $this->formatTextToHtml($validatedData['content']);
        
        $validatedData['is_pinned'] = $request->has('is_pinned');
        $validatedData['is_published'] = $request->has('is_published');
        
        if (!$announcement->is_published && $request->has('is_published')) {
            $validatedData['published_at'] = now();
        }
        
        $announcement->update($validatedData);
        
        return redirect()->route('admin.announcements.index')->with('success', '公告已成功更新');
    }
    
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', '公告已成功刪除');
    }
    
    /**
     * 將純文字轉換為 HTML 段落
     */
    private function formatTextToHtml($text)
    {
        // 先清除可能存在的 HTML 標籤
        $text = strip_tags($text);
        
        // 將換行符號轉換為段落
        $paragraphs = explode("\n\n", $text);
        $html = '';
        
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (!empty($paragraph)) {
                // 處理單個換行符號為 <br>
                $paragraph = nl2br($paragraph);
                $html .= '<p>' . $paragraph . '</p>';
            }
        }
        
        return $html;
    }
}