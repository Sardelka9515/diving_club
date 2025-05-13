<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Announcement;
use App\Models\SearchLog;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        $sort = $request->input('sort');

        // 儲存搜尋紀錄（登入或未登入）
        if ($query) {
            $data = ['keyword' => $query];

            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            } else {
                $data['session_id'] = session()->getId();
            }

            SearchLog::create($data);
        }

        // 使用本地搜尋邏輯
        $activities = Activity::query()
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($sort === 'newest', fn($q) => $q->orderBy('created_at', 'desc'))
            ->get();

        $announcements = Announcement::query()
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->when($sort === 'newest', fn($q) => $q->orderBy('created_at', 'desc'))
            ->get();

        // 撈出最近的搜尋關鍵字
        $recentKeywords = collect();

        if (auth()->check()) {
            $recentKeywords = SearchLog::where('user_id', auth()->id())
                ->latest()->limit(5)->pluck('keyword');
        } else {
            $recentKeywords = SearchLog::where('session_id', session()->getId())
                ->latest()->limit(5)->pluck('keyword');
        }

        return view('search', [
            'query' => $query,
            'sort' => $sort,
            'announcements' => $announcements,
            'activities' => $activities,
            'recentKeywords' => $recentKeywords,
        ]);
    }

    public function clearSearchLogs()
    {
        if (auth()->check()) {
            SearchLog::where('user_id', auth()->id())->delete();
        } else {
            SearchLog::where('session_id', session()->getId())->delete();
        }

        return redirect()->route('search')->with('message', '搜尋紀錄已清除');
    }

}
