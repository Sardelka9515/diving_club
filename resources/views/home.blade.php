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

<style>
    .table-fixed {
        table-layout: fixed;
        width: 100%;
    }

    /* 新增日曆卡片背景圖片樣式 */
    .calendar-card {
        background-image: url('{{ asset('images/beach.jpg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        overflow: hidden;
    }

    .calendar-card .card-body {
        background-color: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(1px);
        padding: 20px;
    }

    .calendar-table th, .calendar-table td {
        background-color: transparent !important;
    }

    /* 非當月的日期欄位完全透明且不顯示日期數字 */
    .calendar-day.text-muted {
        background-color: transparent !important;
        color: transparent !important; /* 隱藏文字 */
        pointer-events: none; /* 禁止互動 */
    }

    /* 當月的日期欄位透明化 */
    .calendar-day {
        background-color: rgba(255, 255, 255, 0.3); /* 半透明背景 */
        height: 90px; /* 保持原有高度 */
        padding: 4px;
        transition: transform 0.2s, background-color 0.2s;
    }

    /* 當月日期的 hover 效果 */
    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* 當天日期的樣式 */
    .calendar-day.bg-light {
        background-color: rgba(200, 200, 200, 0.85) !important; /* 灰色背景 */
    }

    .calendar-events {
        margin-top: 5px;
    }

    .calendar-event .badge {
        white-space: normal;
        line-height: 1.2;
        font-size: 0.75rem;
        display: block;
        transition: all 0.2s;
    }

    .calendar-event .badge:hover {
        transform: translateY(-1px);
    }

    .date-header {
        text-align: right;
        padding-right: 5px;
    }
</style>

<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>每月活動行事曆</h2>
                <div class="d-flex align-items-center">
                    <a href="{{ route('home', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-chevron-left"></i> 上個月
                    </a>
                    <span class="fs-5 mx-2">{{ $currentMonth->format('Y年m月') }}</span>
                    <a href="{{ route('home', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="btn btn-outline-primary btn-sm ms-2">
                        下個月 <i class="bi bi-chevron-right"></i>
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="bi bi-calendar-event"></i> 今天
                    </a>
                </div>
            </div>

            <div class="card calendar-card">
                <div class="card-body">
                    <table class="table table-bordered calendar-table table-fixed">
                        <thead>
                            <tr class="text-center">
                                <th>週日</th>
                                <th>週一</th>
                                <th>週二</th>
                                <th>週三</th>
                                <th>週四</th>
                                <th>週五</th>
                                <th>週六</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $day = $startOfCalendar->copy();
                            @endphp

                            @while ($day <= $endOfCalendar)
                                @if ($day->dayOfWeek === 0)
                                    <tr>
                                @endif

                                <td class="calendar-day {{ $day->month !== $currentMonth->month ? 'text-muted' : '' }} {{ $day->isToday() ? 'bg-light' : '' }}">
                                    <div class="date-header fw-bold">{{ $day->day }}</div>

                                    <div class="calendar-events">
                                        @if(isset($calendarActivities[$day->format('Y-m-d')]))
                                            @foreach($calendarActivities[$day->format('Y-m-d')] as $activity)
                                                <div class="calendar-event">
                                                    <a href="{{ route('activities.show', $activity) }}" class="badge bg-primary w-100">
                                                        {{ $activity->title }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- 填充空白活動區，保持一致的高度 -->
                                            <div class="calendar-event">
                                                <span class="empty-event">&nbsp;</span>
                                            </div>
                                            <div class="calendar-event">
                                                <span class="empty-event">&nbsp;</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                @if ($day->dayOfWeek === 6)
                                    </tr>
                                @endif

                                @php
                                    $day->addDay();
                                @endphp
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 獲取 DOM 元素
    const calendarContainer = document.getElementById('calendar-container');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const todayBtn = document.getElementById('today-btn');
    const currentMonthDisplay = document.getElementById('current-month-display');
    
    // 異步獲取日曆數據
    function loadCalendar(year, month) {
        // 顯示載入中提示
        calendarContainer.innerHTML = '<div class="loader"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">載入中...</p></div>';
        
        // 發送 AJAX 請求
        fetch(`/calendar-data?year=${year}&month=${month}`)
            .then(response => response.json())
            .then(data => {
                // 更新日曆 HTML
                calendarContainer.innerHTML = data.html;
                
                // 更新月份顯示
                currentMonthDisplay.textContent = data.currentMonth;
                
                // 更新按鈕數據屬性
                prevMonthBtn.dataset.year = data.prevMonth.year;
                prevMonthBtn.dataset.month = data.prevMonth.month;
                nextMonthBtn.dataset.year = data.nextMonth.year;
                nextMonthBtn.dataset.month = data.nextMonth.month;
                
                // 更新 URL 而不刷新頁面
                const url = new URL(window.location);
                url.searchParams.set('year', year);
                url.searchParams.set('month', month);
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('載入日曆資料時發生錯誤:', error);
                calendarContainer.innerHTML = '<div class="alert alert-danger">載入日曆時發生錯誤，請重新整理頁面。</div>';
            });
    }
    
    // 綁定按鈕事件
    if(prevMonthBtn) {
        prevMonthBtn.addEventListener('click', function() {
            loadCalendar(this.dataset.year, this.dataset.month);
        });
    }
    
    if(nextMonthBtn) {
        nextMonthBtn.addEventListener('click', function() {
            loadCalendar(this.dataset.year, this.dataset.month);
        });
    }
    
    if(todayBtn) {
        todayBtn.addEventListener('click', function() {
            loadCalendar(this.dataset.year, this.dataset.month);
        });
    }
});
</script>
@endsection