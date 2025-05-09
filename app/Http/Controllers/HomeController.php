<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Announcement;
use Illuminate\Http\Request;

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
            
        return view('home', compact('latestActivities', 'pinnedAnnouncements', 'latestAnnouncements'));
    }
}