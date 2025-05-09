@extends('layouts.app')

@section('title', '首頁')

@section('content')
<div class="jumbotron bg-light p-5 mb-4 rounded">
    <div class="container">
        <h1 class="display-4">歡迎來到潛水社</h1>
        <p class="lead">探索海洋世界的最佳夥伴，加入我們的水下冒險！</p>
        <hr class="my-4">
        <p>立即瀏覽最新活動或成為社員享有更多福利。</p>
        <div class="mt-4">
            <a class="btn btn-primary btn-lg" href="{{ route('activities.index') }}" role="button">瀏覽活動</a>
            @guest
            <a class="btn btn-outline-primary btn-lg ms-2" href="{{ route('register') }}" role="button">立即註冊</a>
            @endguest
        </div>
    </div>
</div>

<div class="container">
    <!-- 最新活動 -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>最新活動</h2>
                <a href="{{ route('activities.index') }}" class="btn btn-outline-primary">查看全部</a>
            </div>
            <div class="row g-4">
                @forelse($latestActivities as $activity)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $activity->title }}</h5>
                            <p class="card-text text-muted">
                                <small>
                                    <i class="bi bi-calendar"></i> {{ $activity->start_date->format('Y/m/d H:i') }}
                                    <br>
                                    <i class="bi bi-geo-alt"></i> {{ $activity->location }}
                                </small>
                            </p>
                            <p class="card-text">{{ Str::limit($activity->description, 100) }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('activities.show', $activity) }}" class="btn btn-primary">查看詳情</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        目前沒有活動
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- 公告區域 -->
    <div class="row">
        <!-- 置頂公告 -->
        <div class="col-md-8 mb-5">
            <h2 class="mb-4">最新公告</h2>
            
            @if(isset($pinnedAnnouncements) && $pinnedAnnouncements->count() > 0)
            <div class="mb-4">
                <h5>置頂公告</h5>
                <div class="list-group">
                    @foreach($pinnedAnnouncements as $announcement)
                    <a href="{{ route('announcements.show', $announcement) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">
                                <i class="bi bi-pin-angle-fill text-danger"></i>
                                {{ $announcement->title }}
                            </h5>
                            <small>{{ $announcement->published_at->format('Y/m/d') }}</small>
                        </div>
                        <p class="mb-1">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="list-group">
                @forelse($latestAnnouncements as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $announcement->title }}</h5>
                        <small>{{ $announcement->published_at->format('Y/m/d') }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                </a>
                @empty
                @if(!isset($pinnedAnnouncements) || $pinnedAnnouncements->count() == 0)
                <div class="alert alert-info">
                    目前沒有公告
                </div>
                @endif
                @endforelse
            </div>
            
            <div class="mt-3">
                <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary">查看全部公告</a>
            </div>
        </div>
        
        <!-- 側邊欄 -->
        <div class="col-md-4 mb-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">關於潛水社</h5>
                </div>
                <div class="card-body">
                    <p>潛水社成立於2010年，致力於推廣潛水運動及海洋保育教育。</p>
                    <p>我們定期舉辦各種潛水活動，從入門體驗到專業認證，適合不同程度的潛水愛好者參與。</p>
                    <p>成為社員可享有更多福利，包括優先報名熱門活動、裝備折扣等。</p>
                    
                    @guest
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary">立即註冊</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">會員登入</a>
                    </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection