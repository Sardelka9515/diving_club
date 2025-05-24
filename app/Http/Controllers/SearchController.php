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

        // 使用本地搜尋邏輯 預設排序方式為 "relevance"
        $sort = $request->input('sort', 'relevance');
        $tab = $request->input('tab', 'activities');

        $activities = collect();
        $announcements = collect();

        if ($tab === 'activities') {
            $activities = Activity::query()
                ->when($query, function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
                })
                ->when($sort === 'relevance', function ($q) use ($query) {
                    // 評估關鍵字在 title 和 content 中出現的次數加總
                    $q->orderByRaw("
                        (
                            (LENGTH(title) - LENGTH(REPLACE(LOWER(title), LOWER(?), ''))) +
                            (LENGTH(content) - LENGTH(REPLACE(LOWER(content), LOWER(?), ''))) +
                            (LENGTH(description) - LENGTH(REPLACE(LOWER(description), LOWER(?), '')))
                        ) DESC
                    ", [$query, $query]);
                })
                ->when($sort === 'newest', fn($q) => $q->orderBy('start_date', 'desc'))
                ->when($sort === 'oldest', fn($q) => $q->orderBy('start_date'))
                ->paginate(5)
                ->appends($request->except('page'));
        } elseif ($tab === 'announcements') {
            $announcements = Announcement::query()
                ->when($query, function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
                })
                ->when($sort === 'relevance', function ($q) use ($query) {
                    // 評估關鍵字在 title 和 content 中出現的次數加總
                    $q->orderByRaw("
                        (
                            (LENGTH(title) - LENGTH(REPLACE(LOWER(title), LOWER(?), ''))) +
                            (LENGTH(content) - LENGTH(REPLACE(LOWER(content), LOWER(?), '')))
                        ) DESC
                    ", [$query, $query]);
                })
                ->when($sort === 'newest', fn($q) => $q->orderBy('published_at', 'desc'))
                ->when($sort === 'oldest', fn($q) => $q->orderBy('published_at'))
                ->paginate(5)
                ->appends($request->except('page'));
        }
        

        $fallbackActivities = Activity::latest()
            ->when($sort === 'newest', fn($q) => $q->orderBy('start_date', 'desc'))
            ->when($sort === 'oldest', fn($q) => $q->orderBy('start_date'))
            ->paginate(5);
        $fallbackAnnouncements = Announcement::latest()                
            ->when($sort === 'newest', fn($q) => $q->orderBy('published_at', 'desc'))
            ->when($sort === 'oldest', fn($q) => $q->orderBy('published_at'))
            ->paginate(5);

        

        // 撈出最近不重複搜尋關鍵字（最新每組 keyword）
        $baseQuery = SearchLog::query()
            ->when(auth()->check(),
                fn ($q) => $q->where('user_id', auth()->id()),
                fn ($q) => $q->where('session_id', $request->session()->getId())
            );

        // 先找出每個 keyword 最新的 id
        $latestIds = $baseQuery
            ->selectRaw('MAX(id) as id')
            ->groupBy('keyword')
            ->pluck('id');

        // 撈出那幾筆資料，照時間排序
        $allKeywords = SearchLog::whereIn('id', $latestIds)
            ->orderByDesc('created_at')
            ->pluck('keyword');

        $recentKeywords = $allKeywords->take(5)->values();
        $reserveKeywords = $allKeywords->slice(5)->values();


        return view('search', [
            'query' => $query,
            'sort' => $sort,
            'tab' => $tab,
            'announcements' => $announcements,
            'activities' => $activities,
            'fallbackActivities' => $fallbackActivities,
            'fallbackAnnouncements' => $fallbackAnnouncements,
            'recentKeywords' => $recentKeywords,
            'reserveKeywords' => $reserveKeywords,
        ]);
    }

    public function deleteLog(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = SearchLog::query();

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $query->where('keyword', $keyword)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function clearLogs(Request $request)
    {
        if (auth()->check()) {
            SearchLog::where('user_id', auth()->id())->delete();
        } else {
            SearchLog::where('session_id', session()->getId())->delete();
        }

        return response()->json(['status' => 'cleared']);
    }

}

