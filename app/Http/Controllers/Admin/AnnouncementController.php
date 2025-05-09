<?php

// app/Http/Controllers/Admin/AnnouncementController.php
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
        
        $validatedData['is_pinned'] = $request->has('is_pinned');
        $validatedData['is_published'] = $request->has('is_published');
        $validatedData['user_id'] = auth()->id();
        $validatedData['published_at'] = $request->has('is_published') ? now() : null;
        
        Announcement::create($validatedData);
        
        return redirect()->route('admin.announcements.index')->with('success', '公告已成功創建');
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
}