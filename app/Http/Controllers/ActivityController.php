<?php

// app/Http/Controllers/ActivityController.php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCategory;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()->where('is_published', true);
        
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        $activities = $query->orderBy('start_date', 'asc')->paginate(9);
        $categories = ActivityCategory::all();
        
        return view('activities.index', compact('activities', 'categories'));
    }
    
    public function show(Activity $activity)
    {
        if (!$activity->is_published) {
            abort(404);
        }
        
        return view('activities.show', compact('activity'));
    }

    // app/Http/Controllers/ActivityController.php
    public function register(Activity $activity, Request $request)
    {
        // 檢查是否在報名時間內
        $now = now();
        if (!$now->between($activity->registration_start, $activity->registration_end)) {
            return back()->with('error', '目前不在報名時間內');
        }
        
        // 檢查是否已報名
        if ($activity->registrations()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', '您已經報名過此活動');
        }
        
        // 檢查人數是否已滿
        $registrationCount = $activity->registrations()->count();
        if ($activity->max_participants > 0 && $registrationCount >= $activity->max_participants) {
            return back()->with('error', '活動報名人數已滿');
        }
        
        try {
            // 建立報名紀錄
            $activity->registrations()->create([
                'user_id' => auth()->id(),
                'status' => 'pending',
            ]);
            
            return back()->with('success', '報名成功');
        } catch (\Exception $e) {
            // 記錄錯誤
            \Log::error('活動報名失敗: ' . $e->getMessage());
            return back()->with('error', '報名處理時發生錯誤，請稍後再試');
        }
        
        
    }

    public function unregister(Activity $activity, Request $request)
    {
        try {
            // 刪除報名紀錄
            $deleted = $activity->registrations()->where('user_id', auth()->id())->delete();
            
            if ($deleted) {
                return back()->with('success', '已取消報名');
            } else {
                return back()->with('error', '您尚未報名此活動');
            }
        } catch (\Exception $e) {
            // 記錄錯誤
            \Log::error('取消活動報名失敗: ' . $e->getMessage());
            return back()->with('error', '取消報名處理時發生錯誤，請稍後再試');
        }
    }
}