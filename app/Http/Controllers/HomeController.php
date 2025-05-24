<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 從請求中獲取年月參數，預設為 2025 年當前月份
        $year = $request->query('year', 2025);
        $month = $request->query('month', now()->month);
        $currentMonth = Carbon::create($year, $month, 1);
        
        // 獲取上一月和下一月，用於導航
        $prevMonth = $currentMonth->copy()->subMonth();
        $nextMonth = $currentMonth->copy()->addMonth();
        
        // 獲取最新活動
        $latestActivities = Activity::where('is_published', true)
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();
            
        // 獲取置頂公告
        $pinnedAnnouncements = Announcement::where('is_published', true)
            ->where('is_pinned', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
            
        // 獲取最新公告
        $latestAnnouncements = Announcement::where('is_published', true)
            ->where('is_pinned', false)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
            
        // 準備日曆資料 - 使用 2025 年的選定月份
        $calendarActivities = Activity::where('is_published', true)
            ->whereMonth('start_date', $currentMonth->month)
            ->whereYear('start_date', $currentMonth->year)
            ->get()
            ->groupBy(function($activity) {
                return $activity->start_date->format('Y-m-d');
            });
            
        // 計算日曆顯示範圍 - 確保從星期日開始，到星期六結束
        $firstDayOfMonth = Carbon::create($currentMonth->year, $currentMonth->month, 1);
        $daysInMonth = $currentMonth->daysInMonth;
        
        // 明確使用數字 0 表示星期日
        $startOfCalendar = $firstDayOfMonth->copy()->startOfWeek(0);
        $endOfCalendar = $firstDayOfMonth->copy()->endOfMonth()->endOfWeek(6);
        
        return view('home', compact(
            'latestActivities', 
            'pinnedAnnouncements', 
            'latestAnnouncements',
            'currentMonth',
            'calendarActivities',
            'firstDayOfMonth',
            'daysInMonth',
            'startOfCalendar',
            'endOfCalendar',
            'prevMonth',
            'nextMonth'
        ));
    }
}