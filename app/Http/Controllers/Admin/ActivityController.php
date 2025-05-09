<?php

// app/Http/Controllers/Admin/ActivityController.php
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
        $categories = ActivityCategory::all();
        return view('admin.activities.create', compact('categories'));
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
            'activity_category_id' => 'required|exists:activity_categories,id',
            'is_published' => 'boolean',
        ]);
        
        $validatedData['is_published'] = $request->has('is_published');
        
        Activity::create($validatedData);
        
        return redirect()->route('admin.activities.index')->with('success', '活動已成功創建');
    }
    
    public function edit(Activity $activity)
    {
        $categories = ActivityCategory::all();
        return view('admin.activities.edit', compact('activity', 'categories'));
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
            'activity_category_id' => 'required|exists:activity_categories,id',
            'is_published' => 'boolean',
        ]);
        
        $validatedData['is_published'] = $request->has('is_published');
        
        $activity->update($validatedData);
        
        return redirect()->route('admin.activities.index')->with('success', '活動已成功更新');
    }
    
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', '活動已成功刪除');
    }
}