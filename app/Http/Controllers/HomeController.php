<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
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
            
        // 準備日曆資料
        $currentMonth = Carbon::now();
        $calendarActivities = Activity::where('is_published', true)
            ->whereMonth('start_date', $currentMonth->month)
            ->whereYear('start_date', $currentMonth->year)
            ->get()
            ->groupBy(function($activity) {
                return $activity->start_date->format('Y-m-d');
            });
            
        $firstDayOfMonth = Carbon::create($currentMonth->year, $currentMonth->month, 1);
        $daysInMonth = $currentMonth->daysInMonth;
        $startOfCalendar = $firstDayOfMonth->copy()->startOfWeek();
        $endOfCalendar = $firstDayOfMonth->copy()->endOfMonth()->endOfWeek();
        
        return view('home', compact(
            'latestActivities', 
            'pinnedAnnouncements', 
            'latestAnnouncements',
            'currentMonth',
            'calendarActivities',
            'firstDayOfMonth',
            'daysInMonth',
            'startOfCalendar',
            'endOfCalendar'
        ));
    }
}