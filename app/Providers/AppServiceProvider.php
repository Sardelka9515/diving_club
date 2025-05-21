<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SearchLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (!app()->runningInConsole() && !request()->ajax()) {
            View::composer('*', function ($view) {
                $query = SearchLog::query();

                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', session()->getId());
                }

                // 撈所有紀錄，照時間從新到舊排，再去除重複 keyword
                $all = $query
                    ->orderByDesc('created_at')
                    ->get()
                    ->map(fn($item) => trim(mb_strtolower($item->keyword)))
                    ->unique()
                    ->values();

                // 切出前 5 筆和備用筆（第 6 筆以後）
                $recentKeywords = $all->take(5);
                $reserveKeywords = $all->slice(5)->values();

                $view->with(compact('recentKeywords', 'reserveKeywords'));
            });
        }
    }

}
