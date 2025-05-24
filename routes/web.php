<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\SearchController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AnnouncementController;

// 首頁路由
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 測試頁面（保留）
Route::get('/test-page', function () {
    $isLoggedIn = Auth::check();
    $user = $isLoggedIn ? Auth::user() : null;
    $roles = $isLoggedIn ? $user->roles()->pluck('name')->toArray() : [];

    return view('test-page', compact('isLoggedIn', 'user', 'roles'));
});

// 身份驗證路由（login, register等）
require __DIR__ . '/auth.php';

// 儀表板
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 個人資料相關
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/comments', [CommentController::class, 'memberComments'])->name('member.comments');
});

// 活動相關
Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');

// 公告相關
Route::get('/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');

// 會員功能
Route::middleware(['auth'])->group(function () {
    Route::get('/my-activities', [App\Http\Controllers\MemberController::class, 'activities'])->name('member.activities');
    Route::post('/activities/{activity}/register', [App\Http\Controllers\ActivityController::class, 'register'])->name('activities.register');
    Route::delete('/activities/{activity}/unregister', [App\Http\Controllers\ActivityController::class, 'unregister'])->name('activities.unregister');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/activities/{activity}/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/report', [App\Http\Controllers\CommentController::class, 'report'])->name('comments.report');
    Route::patch('/comments/{comment}/toggle-visibility', [App\Http\Controllers\CommentController::class, 'toggleVisibility'])
        ->name('comments.toggle-visibility');
});

// 管理員後台路由 - 根據角色分配權限
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // 儀表板 - admin 和 super 都可使用
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->middleware('role:admin,super')
        ->name('dashboard');

    // 活動管理 - admin 和 super 可使用
    Route::resource('activities', App\Http\Controllers\Admin\ActivityController::class)
        ->middleware('role:admin,super');

    // 公告管理 - admin 和 super 可使用  
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class)
        ->middleware('role:admin,super');

    // Comment 管理 - admin 和 super 可使用
    Route::middleware(['auth', 'role:admin,super'])->group(function () {
        Route::get('/comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
        Route::get('/comments/{comment}/edit', [App\Http\Controllers\Admin\CommentController::class, 'edit'])->name('comments.edit');
        Route::put('/comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'update'])->name('comments.update');
        Route::delete('/comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
        Route::patch('/comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::patch('/comments/{comment}/reject', [App\Http\Controllers\Admin\CommentController::class, 'reject'])->name('comments.reject');
        Route::get('/comments/reported', [App\Http\Controllers\Admin\CommentController::class, 'reported'])->name('comments.reported');
        Route::patch('/comments/{comment}/toggle-visibility', [App\Http\Controllers\Admin\CommentController::class, 'toggleVisibility'])
            ->name('comments.toggle-visibility');
        Route::patch('/comments/{comment}/clear-reported', [App\Http\Controllers\Admin\CommentController::class, 'clearReported'])
            ->name('comments.clear-reported');
    });

    Route::middleware(['auth', 'role:admin,super'])->group(function () {
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::patch('/reports/{report}/resolve', [App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');
        Route::patch('/reports/{report}/reject', [App\Http\Controllers\Admin\ReportController::class, 'reject'])->name('reports.reject');
        Route::patch('/reports/{report}/reopen', [App\Http\Controllers\Admin\ReportController::class, 'reopen'])
            ->name('reports.reopen');
    });

    // 超級管理員專用功能
    Route::middleware('role:super')->group(function () {
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');
    });
});

// 搜尋功能
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::post('/search/clear', [SearchController::class, 'clearSearchLogs'])->name('search.clearLogs');
// 單筆刪除
Route::post('/search/logs/delete', [SearchController::class, 'deleteLog']);
Route::get('/search/logs/recent', [SearchController::class, 'refreshRecent']);


// 清空全部
Route::post('/search/logs/clear', [SearchController::class, 'clearLogs']);
Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
Route::get('/search/logs/recent', [SearchController::class, 'recentLogs']);

