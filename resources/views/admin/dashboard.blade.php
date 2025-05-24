@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>系統儀表板</h1>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">用戶數量</h6>
                                <h2>{{ $stats['users'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">活動數量</h6>
                                <h2>{{ $stats['activities'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-calendar-event fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">公告數量</h6>
                                <h2>{{ $stats['announcements'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-megaphone fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">報名次數</h6>
                                <h2>{{ $stats['registrations'] ?? 0 }}</h2>
                            </div>
                            <i class="bi bi-person-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <h6 class="card-title">評論總數</h6>
                                <p class="h2">{{ App\Models\Comment::count() }}</p>
                            </div>
                            <i class="bi bi-chat-dots fs-1"></i>
                        </div>
                        <div class="small mt-2">
                            <span class="badge bg-light text-dark">今日:
                                {{ App\Models\Comment::whereDate('created_at', today())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">快速操作</h5>
                    </div>
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div class="col">
                                <a href="{{ route('admin.activities.create') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-plus-circle fs-1 text-primary mb-2"></i>
                                            <h5>新增活動</h5>
                                            <p class="text-muted small">創建新的活動</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('admin.announcements.create') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-megaphone-fill fs-1 text-success mb-2"></i>
                                            <h5>新增公告</h5>
                                            <p class="text-muted small">發布新的公告</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('admin.activities.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-list-ul fs-1 text-info mb-2"></i>
                                            <h5>管理活動</h5>
                                            <p class="text-muted small">查看所有活動</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col">
                                <a href="{{ route('admin.announcements.index') }}" class="text-decoration-none">
                                    <div class="card h-100 border-0 bg-light">
                                        <div class="card-body text-center">
                                            <i class="bi bi-list-check fs-1 text-warning mb-2"></i>
                                            <h5>管理公告</h5>
                                            <p class="text-muted small">查看所有公告</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">最新活動</h5>
                    </div>
                    <div class="card-body">
                        @if (isset($latestActivities) && $latestActivities->count() > 0)
                            <div class="list-group">
                                @foreach ($latestActivities as $activity)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $activity->title }}</h6>
                                            <small
                                                class="text-muted">{{ $activity->start_date->format('Y/m/d H:i') }}</small>
                                        </div>
                                        <div>
                                            <span
                                                class="badge bg-{{ $activity->is_published ? 'success' : 'warning' }} rounded-pill">
                                                {{ $activity->is_published ? '已發布' : '草稿' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">目前沒有活動</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
