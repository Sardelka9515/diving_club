<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'activities' => Activity::count(),
            'announcements' => Announcement::count(),
            'registrations' => Registration::count(),
        ];
        
        $latestActivities = Activity::latest()->take(5)->get();
        $latestAnnouncements = Announcement::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'latestActivities', 'latestAnnouncements'));
    }
}