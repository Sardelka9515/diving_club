<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// 首頁路由 (不需要登入)
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/my-activities', [App\Http\Controllers\MemberController::class, 'activities'])->name('member.activities');
    Route::post('/activities/{activity}/register', [App\Http\Controllers\ActivityController::class, 'register'])->name('activities.register');
    Route::delete('/activities/{activity}/unregister', [App\Http\Controllers\ActivityController::class, 'unregister'])->name('activities.unregister');
});

// 管理後台路由
Route::middleware(['auth', 'role:admin,super'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('activities', App\Http\Controllers\Admin\ActivityController::class);
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
});

Route::middleware(['auth', 'role:super'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
});

Route::get('/activities', [App\Http\Controllers\ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [App\Http\Controllers\ActivityController::class, 'show'])->name('activities.show');
Route::middleware(['auth', 'role:admin,super'])->group(function () {
    Route::resource('admin/activities', App\Http\Controllers\Admin\ActivityController::class, ['as' => 'admin']);
});

Route::get('/announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/announcements/{announcement}', [App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
Route::middleware(['auth', 'role:admin,super'])->group(function () {
    Route::resource('admin/announcements', App\Http\Controllers\Admin\AnnouncementController::class, ['as' => 'admin']);
});

// 超級管理員路由
Route::middleware(['auth', 'role:super'])->prefix('admin')->name('admin.')->group(function () {
    // 用戶管理
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // 角色管理
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    
    // 權限管理
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
    
    // 系統設定
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');
});



require __DIR__.'/auth.php';
