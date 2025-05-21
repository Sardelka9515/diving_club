@extends('layouts.app')

@section('title', '會員儀表板')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">歡迎回來，{{ Auth::user()->name }}！</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- 快速統計 -->
                            <div class="col-md-2 mb-4">
                                <div class="card bg-primary text-white h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <h5>我的活動報名</h5>
                                        <p class="h3">{{ Auth::user()->registrations()->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-4">
                                <div class="card bg-success text-white h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4">
                                            <i class="bi bi-check-circle"></i>
                                        </div>
                                        <h5>已確認活動</h5>
                                        <p class="h3">
                                            {{ Auth::user()->registrations()->where('status', 'approved')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-4">
                                <div class="card bg-warning text-white h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <h5>待審核活動</h5>
                                        <p class="h3">
                                            {{ Auth::user()->registrations()->where('status', 'pending')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-4">
                                <div class="card bg-info text-white h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <h5>最新公告</h5>
                                        <p class="h3">
                                            {{ App\Models\Announcement::where('is_published', true)->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-4">
                                <div class="card bg-secondary text-white h-100">
                                    <div class="card-body text-center">
                                        <div class="display-4">
                                            <i class="bi bi-chat-dots"></i>
                                        </div>
                                        <h5>我的評論</h5>
                                        <p class="h3">{{ Auth::user()->comments()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 最近活動 -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">我的最近報名</div>
                                    <div class="card-body">
                                        @php
                                            $recentRegistrations = Auth::user()
                                                ->registrations()
                                                ->with('activity')
                                                ->latest()
                                                ->take(5)
                                                ->get();
                                        @endphp

                                        @if ($recentRegistrations->count() > 0)
                                            <div class="list-group">
                                                @foreach ($recentRegistrations as $registration)
                                                    <div
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="mb-1">{{ $registration->activity->title }}</h6>
                                                            <small
                                                                class="text-muted">{{ $registration->activity->start_date->format('Y/m/d H:i') }}</small>
                                                        </div>
                                                        <span
                                                            class="badge bg-{{ $registration->status == 'approved' ? 'success' : ($registration->status == 'pending' ? 'warning' : 'danger') }} rounded-pill">
                                                            {{ $registration->status == 'approved' ? '已確認' : ($registration->status == 'pending' ? '待審核' : '已拒絕') }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('member.activities') }}" class="btn btn-primary">查看全部</a>
                                            </div>
                                        @else
                                            <p class="text-muted">您尚未報名任何活動</p>
                                            <a href="{{ route('activities.index') }}" class="btn btn-primary">瀏覽活動</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">最新公告</div>
                                    <div class="card-body">
                                        @php
                                            $latestAnnouncements = App\Models\Announcement::where('is_published', true)
                                                ->orderBy('published_at', 'desc')
                                                ->take(5)
                                                ->get();
                                        @endphp

                                        @if ($latestAnnouncements->count() > 0)
                                            <div class="list-group">
                                                @foreach ($latestAnnouncements as $announcement)
                                                    <a href="{{ route('announcements.show', $announcement) }}"
                                                        class="list-group-item list-group-item-action">
                                                        <div class="d-flex w-100 justify-content-between">
                                                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                                                            <small>{{ $announcement->published_at->format('Y/m/d') }}</small>
                                                        </div>
                                                        @if ($announcement->is_pinned)
                                                            <span class="badge bg-danger">置頂</span>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('announcements.index') }}"
                                                    class="btn btn-primary">查看全部</a>
                                            </div>
                                        @else
                                            <p class="text-muted">目前沒有公告</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
