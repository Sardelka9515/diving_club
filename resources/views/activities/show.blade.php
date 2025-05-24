@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <div class="container">
        <!-- Breadcrumb navigation -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('activities.index') }}"><i class="bi bi-calendar-event"></i>
                        活動列表</a></li>
                <li class="breadcrumb-item active">{{ $activity->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Activity Information Section -->
            <div class="col-lg-8">
                <div class="card ocean-card" data-aos="fade-up">
                    <div class="card-body p-4">
                        <!-- Activity header with title and category -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h1 class="mb-2">{{ $activity->title }}</h1>
                                <span class="badge rounded-pill"
                                    style="background-color: var(--ocean-medium);">{{ $activity->category->name }}</span>
                            </div>

                            <!-- Share buttons -->
                            <div class="d-flex gap-2 mt-1">
                                <a href="#" class="btn btn-sm btn-light rounded-circle" title="分享到臉書">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-light rounded-circle" title="分享到LINE">
                                    <i class="bi bi-line"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-light rounded-circle" title="複製連結">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Activity key details cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-3">
                                <div class="info-card p-3 rounded-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-circle-sm me-2">
                                            <i class="bi bi-calendar-check text-primary"></i>
                                        </div>
                                        <p class="mb-0 small text-muted">活動時間</p>
                                    </div>
                                    <p class="mb-0 fw-semibold small">{{ $activity->start_date->format('Y/m/d H:i') }}<br>至
                                        {{ $activity->end_date->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="info-card p-3 rounded-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-circle-sm me-2">
                                            <i class="bi bi-geo-alt text-primary"></i>
                                        </div>
                                        <p class="mb-0 small text-muted">活動地點</p>
                                    </div>
                                    <p class="mb-0 fw-semibold small">{{ $activity->location }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="info-card p-3 rounded-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-circle-sm me-2">
                                            <i class="bi bi-cash-coin text-primary"></i>
                                        </div>
                                        <p class="mb-0 small text-muted">活動費用</p>
                                    </div>
                                    <p class="mb-0 fw-semibold">NT$ {{ number_format($activity->price) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="info-card p-3 rounded-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="icon-circle-sm me-2">
                                            <i class="bi bi-people text-primary"></i>
                                        </div>
                                        <p class="mb-0 small text-muted">人數上限</p>
                                    </div>
                                    <p class="mb-0 fw-semibold">{{ $activity->max_participants }} 人</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <p class="mb-2 fw-medium">報名時間</p>
                            <div class="registration-timeline p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="registration-dot active"></div>
                                    <div class="registration-line"></div>
                                    <div
                                        class="registration-dot {{ now()->isAfter($activity->registration_end) ? 'active' : '' }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <div class="text-center">
                                        <p class="mb-0 small fw-medium">開始報名</p>
                                        <p class="mb-0 small text-muted">
                                            {{ $activity->registration_start->format('Y/m/d H:i') }}</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="mb-0 small fw-medium">截止報名</p>
                                        <p class="mb-0 small text-muted">
                                            {{ $activity->registration_end->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity content -->
                        <hr class="my-4">

                        <div>
                            <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>活動內容</h5>
                            <div class="activity-content">
                                {!! $activity->content !!}
                            </div>
                        </div>

                        <!-- Activity organizer info (if available) -->
                        <hr class="my-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg bg-light">
                                <i class="bi bi-person-badge fs-4 text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <p class="small text-muted mb-0">活動組織者</p>
                                <h6 class="mb-0">潛水社工作團隊</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Sidebar -->
            <div class="col-lg-4">
                @php
                    // 1. 設定一致的時區常量
                    $APP_TIMEZONE = config('app.timezone');

                    // 2. 設置當前時間並確保時區一致
                    $now = \Carbon\Carbon::now()->setTimezone($APP_TIMEZONE);

                    // 3. 轉換所有活動時間到同一時區
                    $activityStart = $activity->start_date->setTimezone($APP_TIMEZONE);
                    $activityEnd = $activity->end_date->setTimezone($APP_TIMEZONE);
                    $registrationStart = $activity->registration_start->setTimezone($APP_TIMEZONE);
                    $registrationEnd = $activity->registration_end->setTimezone($APP_TIMEZONE);

                    // 4. 更新判斷邏輯，使用統一時區的時間對象
                    $canRegister = $now->between($registrationStart, $registrationEnd);
                    $registrationCount = $activity->registrations()->count();
                    $isFull = $activity->max_participants > 0 && $registrationCount >= $activity->max_participants;
                    $userRegistered = auth()->check()
                        ? $activity
                            ->registrations()
                            ->where('user_id', auth()->id())
                            ->exists()
                        : false;

                    // 5. 活動狀態判斷
                    $status = 'open';
                    if ($isFull) {
                        $status = 'full';
                    }
                    if (!$canRegister && $now->isAfter($activityEnd)) {
                        $status = 'completed';
                    } elseif (!$canRegister && $now->isBefore($registrationStart)) {
                        $status = 'upcoming';
                    } elseif (!$canRegister && $now->isAfter($registrationEnd)) {
                        $status = 'closed';
                    }

                    // 6. 時間差格式化函數
                    function formatDiff($diff)
                    {
                        $parts = [];
                        if ($diff->m) {
                            $parts[] = "{$diff->m} 個月";
                        }
                        if ($diff->d) {
                            $parts[] = "{$diff->d} 天";
                        }
                        if ($diff->h > 0 || $diff->d > 0 || $diff->m > 0) {
                            $parts[] = "{$diff->h} 小時";
                        }
                        if ($diff->i > 0 || $diff->h > 0 || $diff->d > 0 || $diff->m > 0) {
                            $parts[] = "{$diff->i} 分鐘";
                        }
                        if (empty($parts) || count($parts) === 0) {
                            $parts[] = "{$diff->s} 秒";
                        }
                        return implode(' ', $parts);
                    }

                    // 7. 計算時間差並生成提示訊息
                    if ($now->lt($registrationStart)) {
                        $diff = $now->diff($registrationStart);
                        $message = '還有 ' . formatDiff($diff) . ' 開放報名';
                    } elseif ($now->lt($registrationEnd)) {
                        $diff = $now->diff($registrationEnd);
                        $message = '剩餘 ' . formatDiff($diff) . ' 截止報名';

                        // 處理即將截止的情況
                        $totalSeconds = $diff->days * 86400 + $diff->h * 3600 + $diff->i * 60 + $diff->s;
                        if ($totalSeconds < 300) {
                            // 少於5分鐘
                            $message =
                                '<span class="text-danger fw-bold">即將截止！剩餘 ' . formatDiff($diff) . '</span>';
                        }
                    } else {
                        $message = '';
                    }
                @endphp

                <!-- Registration Card -->
                <div class="card ocean-card sticky-top" style="top: 20px; z-index: 100;" data-aos="fade-up"
                    data-aos-delay="100">
                    <div class="card-header bg-transparent d-flex align-items-center border-0 pb-0">
                        <div class="wave-icon me-2" style="width: 28px; height: 28px;">
                            <i class="bi bi-calendar-check" style="font-size: 14px;"></i>
                        </div>
                        <h5 class="mb-0">報名資訊</h5>
                    </div>
                    <div class="card-body p-4">
                        <!-- Registration status badge -->
                        <div class="d-flex justify-content-between align-items-center mb-3 gap-4">
                            <span class="badge status-badge status-{{ $status }} rounded-pill px-3 py-2">
                                @if ($status == 'open')
                                    <i class="bi bi-unlock me-1"></i> 開放報名中
                                @elseif ($status == 'full')
                                    <i class="bi bi-people-fill me-1"></i> 名額已滿
                                @elseif ($status == 'upcoming')
                                    <i class="bi bi-hourglass me-1"></i> 即將開放報名
                                @elseif ($status == 'closed')
                                    <i class="bi bi-lock me-1"></i> 報名已截止
                                @elseif ($status == 'completed')
                                    <i class="bi bi-check-circle me-1"></i> 活動已結束
                                @endif
                            </span>
                            {{-- <div>
                                {{ $diff }}
                                {{ $diff->days > 0 ? $diff->days . ' 天' : '' }}
                                {{ $diff->h > 0 ? $diff->h . ' 小時' : '' }}
                                {{ $diff->i > 0 ? $diff->i . ' 分鐘' : '' }}
                                {{ $diff->s > 0 ? $diff->s . ' 秒' : '' }}
                            </div> --}}
                            <span class="text-muted small text-center">
                                {!! $message ? $message : '活動已結束' !!}
                            </span>
                        </div>

                        <!-- Registration progress bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <p class="mb-0 small fw-medium">目前報名狀況</p>
                                <p class="mb-0 text-primary fw-bold">
                                    {{ $registrationCount }}/{{ $activity->max_participants > 0 ? $activity->max_participants : '不限' }}
                                </p>
                            </div>
                            <div class="progress"
                                style="height: 12px; border-radius: 6px; background-color: var(--ocean-foam);">
                                @if ($activity->max_participants > 0)
                                    @php
                                        $percentage = min(
                                            100,
                                            ($registrationCount / $activity->max_participants) * 100,
                                        );
                                    @endphp
                                    <div class="progress-bar bg-gradient" role="progressbar"
                                        style="width: {{ $percentage }}%; background: linear-gradient(90deg, var(--ocean-light), var(--ocean-medium));"
                                        aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                @else
                                    <div class="progress-bar bg-gradient" role="progressbar"
                                        style="width: 100%; background: linear-gradient(90deg, var(--ocean-light), var(--ocean-medium));"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Registration button/status -->
                        <div class="registration-actions">
                            @if (!auth()->check())
                                <div class="alert alert-info d-flex align-items-center mb-3" role="alert">
                                    <i class="bi bi-info-circle me-2 fs-5"></i>
                                    <div>請先登入才能報名活動</div>
                                </div>
                                <a href="{{ route('login') }}" class="btn btn-ocean w-100">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> 登入報名
                                </a>
                            @elseif($userRegistered)
                                <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                                    <div>您已成功報名此活動</div>
                                </div>
                                <form action="{{ route('activities.unregister', $activity) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-danger w-100 d-flex align-items-center justify-content-center"
                                        onclick="return confirm('確定要取消報名嗎？這可能會失去您的名額')">
                                        <i class="bi bi-x-circle me-2"></i> 取消報名
                                    </button>
                                </form>
                            @elseif(!$canRegister)
                                <div class="alert {{ $now->isAfter($activity->end_date) ? 'alert-secondary' : 'alert-warning' }} d-flex align-items-center mb-3"
                                    role="alert">
                                    <i class="bi bi-exclamation-triangle me-2 fs-5"></i>
                                    <div>
                                        {{ $now->isAfter($activity->end_date) ? '活動已結束' : ($now->isAfter($activity->registration_end) ? '報名時間已截止' : '報名尚未開始') }}
                                    </div>
                                </div>
                                <button class="btn btn-secondary w-100" disabled>
                                    {{ $now->isAfter($activity->end_date) ? '活動已結束' : '無法報名' }}
                                </button>
                            @elseif($isFull)
                                <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                                    <i class="bi bi-people-fill me-2 fs-5"></i>
                                    <div>很抱歉，報名人數已滿</div>
                                </div>
                                <button class="btn btn-secondary w-100" disabled>
                                    名額已滿
                                </button>
                            @else
                                <div class="alert alert-primary d-flex align-items-center mb-3" role="alert">
                                    <i class="bi bi-check-circle me-2 fs-5"></i>
                                    <div>此活動正開放報名中</div>
                                </div>
                                <form action="{{ route('activities.register', $activity) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-ocean btn-lg w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-plus me-2"></i> 立即報名
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Related activities or additional info (optional) -->
                        <div class="mt-4">
                            <h6 class="mb-3 border-bottom pb-2">其他相關資訊</h6>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-sm btn-light flex-grow-1">
                                    <i class="bi bi-question-circle me-1"></i> 常見問題
                                </a>
                                <a href="#" class="btn btn-sm btn-light flex-grow-1">
                                    <i class="bi bi-envelope me-1"></i> 聯絡我們
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Discussion Section -->
            <div class="col-12">
                <div class="card ocean-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="card-header bg-transparent border-0 d-flex align-items-center pb-0">
                        <div class="wave-icon me-2" style="width: 28px; height: 28px;">
                            <i class="bi bi-chat-dots" style="font-size: 14px;"></i>
                        </div>
                        <h5 class="mb-0">活動討論區</h5>
                    </div>

                    <div class="card-body p-4">
                        @auth
                            <form action="{{ route('comments.store', $activity) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar me-2">
                                            @if (auth()->user()->hasRole('admin'))
                                                <div class="avatar avatar-admin has-badge">
                                                    <i class="bi bi-shield-fill"></i>
                                                </div>
                                            @elseif(auth()->user()->hasRole('super'))
                                                <div class="avatar avatar-super has-badge">
                                                    <i class="bi bi-star-fill"></i>
                                                </div>
                                            @elseif(auth()->user()->hasRole('member'))
                                                <div class="avatar avatar-member has-badge">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>
                                            @elseif(auth()->user()->hasRole('user'))
                                                <div class="avatar avatar-user has-badge">
                                                    <i class="bi bi-flag-fill"></i>
                                                </div>
                                            @else
                                                <div class="avatar avatar-user">
                                                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="mb-0">以 {{ auth()->user()->name }} 的身份發表評論</p>
                                    </div>
                                    <textarea class="form-control ocean-form @error('content') is-invalid @enderror" id="content" name="content"
                                        rows="3" placeholder="分享您對此活動的看法或問題..."></textarea>
                                    @error('content')
                                        <div class="invalid-feedback">Invalid feedback</div>
                                    @enderror
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-ocean">
                                        <i class="bi bi-chat-dots-fill me-1"></i> 發表評論
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                <div>請<a href="{{ route('login') }}" class="alert-link">登入</a>後參與討論。</div>
                            </div>
                        @endauth

                        <hr class="my-4">

                        <!-- 評論列表 -->
                        <div class="comments-list">
                            <!-- 只顯示已批准且可見的評論 -->
                            @if ($activity->comments()->approved()->visible()->whereNull('parent_id')->count() > 0)
                                @foreach ($activity->comments()->approved()->visible()->whereNull('parent_id')->latest()->get() as $comment)
                                    <div class="comment-item mb-4 bg-white p-4 rounded-3 shadow-sm"
                                        id="comment-{{ $comment->id }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0">
                                                <div class="flex-shrink-0">
                                                    @if ($comment->user->hasRole('admin'))
                                                        <div class="avatar avatar-admin has-badge">
                                                            <i class="bi bi-shield-fill"></i>
                                                        </div>
                                                    @elseif($comment->user->hasRole('super'))
                                                        <div class="avatar avatar-super has-badge">
                                                            <i class="bi bi-star-fill"></i>
                                                        </div>
                                                    @elseif($comment->user->hasRole('member'))
                                                        <div class="avatar avatar-member has-badge">
                                                            <i class="bi bi-mortarboard-fill"></i>
                                                        </div>
                                                    @elseif($comment->user->hasRole('user'))
                                                        <div class="avatar avatar-user has-badge">
                                                            <i class="bi bi-flag-fill"></i>
                                                        </div>
                                                    @else
                                                        <div class="avatar avatar-user">
                                                            <span>{{ substr($comment->user->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div
                                                    class="comment-header d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        <small
                                                            class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                        @if ($comment->reports()->exists())
                                                            @php
                                                                $report = $comment->reports()->first();
                                                                $status = $report->status; // 'pending', 'resolved'
                                                            @endphp
                                                            @if ($status == 'pending')
                                                                <span class="ms-2 badge bg-warning" title="你已舉報此評論">
                                                                    <i class="bi bi-flag-fill"></i>
                                                                    管理員審查中
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="comment-content my-2">
                                                    <p class="mb-1">{{ $comment->content }}</p>
                                                </div>

                                                <div class="comment-actions">
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <button class="btn btn-sm btn-light reply-btn"
                                                            data-comment-id="{{ $comment->id }}">
                                                            <i class="bi bi-reply"></i> 回覆
                                                        </button>

                                                        @can('update', $comment)
                                                            <button class="btn btn-sm btn-light edit-btn"
                                                                data-comment-id="{{ $comment->id }}"
                                                                data-comment-content="{{ $comment->content }}">
                                                                <i class="bi bi-pencil"></i> 編輯
                                                            </button>

                                                            <!-- 添加顯示/隱藏按鈕 -->
                                                            <form action="{{ route('comments.toggle-visibility', $comment) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-light">
                                                                    <i
                                                                        class="bi {{ $comment->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                                    {{ $comment->is_visible ? '隱藏' : '顯示' }}
                                                                </button>
                                                            </form>
                                                        @endcan

                                                        @can('delete', $comment)
                                                            <form action="{{ route('comments.destroy', $comment) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-light"
                                                                    onclick="return confirm('確定要刪除此評論嗎？此操作無法復原')">
                                                                    <i class="bi bi-trash"></i> 刪除
                                                                </button>
                                                            </form>
                                                        @endcan

                                                        @php
                                                            $report = $comment
                                                                ->reports()
                                                                ->where('user_id', auth()->id())
                                                                ->first();
                                                        @endphp
                                                        @if ($report)
                                                            <form action="{{ route('comments.unreport', $comment) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-light"
                                                                    onclick="return confirm('確定要取消舉報此評論嗎？')">
                                                                    <i class="bi bi-flag-fill"></i> 取消舉報
                                                                </button>
                                                            </form>
                                                        @elseif ($comment->user->id !== auth()->id())
                                                            @auth
                                                                <button class="btn btn-sm btn-light report-btn"
                                                                    data-comment-id="{{ $comment->id }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#reportModal-{{ $comment->id }}">
                                                                    <i class="bi bi-flag"></i> 舉報
                                                                </button>

                                                                @push('modals')
                                                                    <!-- 舉報彈窗 -->
                                                                    <x-report-modal :comment="$comment" :commentId="$comment->id"
                                                                        :content="$comment->content" />
                                                                @endpush
                                                            @endauth
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- 編輯表單 (預設隱藏) -->
                                                <div class="edit-form mt-3 p-3 rounded bg-light"
                                                    id="edit-form-{{ $comment->id }}" style="display: none;">
                                                    <form action="{{ route('comments.update', $comment) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <textarea class="form-control ocean-form" name="content" rows="3">{{ $comment->content }}</textarea>
                                                        </div>
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-secondary cancel-edit-btn"
                                                                data-comment-id="{{ $comment->id }}">
                                                                <i class="bi bi-x"></i> 取消
                                                            </button>
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="bi bi-check2"></i> 更新評論
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- 回覆表單 (預設隱藏) -->
                                                <div class="reply-form mt-3 p-3 rounded bg-light"
                                                    id="reply-form-{{ $comment->id }}" style="display: none;">
                                                    @auth
                                                        <form action="{{ route('comments.reply', $comment) }}"
                                                            method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <textarea class="form-control ocean-form" name="content" rows="2"
                                                                    placeholder="回覆 {{ $comment->user->name }}..."></textarea>
                                                            </div>
                                                            <div class="d-flex justify-content-end gap-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-secondary cancel-reply-btn"
                                                                    data-comment-id="{{ $comment->id }}">
                                                                    <i class="bi bi-x"></i> 取消
                                                                </button>
                                                                <button type="submit" class="btn btn-sm btn-primary">
                                                                    <i class="bi bi-reply-fill"></i> 發表回覆
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <div class="alert alert-info">
                                                            請<a href="{{ route('login') }}" class="alert-link">登入</a>後回覆。
                                                        </div>
                                                    @endauth
                                                </div>

                                                <!-- 回覆列表 -->
                                                @if ($comment->replies()->approved()->visible()->count() > 0)
                                                    <div class="replies mt-3">
                                                        @foreach ($comment->replies()->approved()->visible()->get() as $reply)
                                                            <div class="reply bg-light p-3 rounded ms-2 mb-2 border-start border-4"
                                                                style="border-color: var(--ocean-light) !important;"
                                                                id="comment-{{ $reply->id }}">
                                                                <div class="d-flex">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="avatar avatar-sm">
                                                                            @if ($reply->user->hasRole('admin'))
                                                                                <div class="avatar avatar-admin has-badge">
                                                                                    <i class="bi bi-shield-fill"></i>
                                                                                </div>
                                                                            @elseif($reply->user->hasRole('super'))
                                                                                <div class="avatar avatar-super has-badge">
                                                                                    <i class="bi bi-star-fill"></i>
                                                                                </div>
                                                                            @elseif($reply->user->hasRole('member'))
                                                                                <div
                                                                                    class="avatar avatar-member has-badge">
                                                                                    <i class="bi bi-mortarboard-fill"></i>
                                                                                </div>
                                                                            @elseif($reply->user->hasRole('user'))
                                                                                <div class="avatar avatar-user has-badge">
                                                                                    <i class="bi bi-flag-fill"></i>
                                                                                </div>
                                                                            @else
                                                                                <div class="avatar avatar-user">
                                                                                    <span>{{ substr($reply->user->name, 0, 1) }}</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-2">
                                                                        <div
                                                                            class="reply-header d-flex justify-content-between align-items-center">
                                                                            <h6 class="mb-0 small">
                                                                                {{ $reply->user->name }}</h6>
                                                                            <div
                                                                                class="d-flex flex-column align-items-center gap-1">
                                                                                <small
                                                                                    class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                                @if ($reply->reports()->exists())
                                                                                    @php
                                                                                        $report = $reply
                                                                                            ->reports()
                                                                                            ->first();
                                                                                        $status = $report->status; // 'pending', 'resolved'
                                                                                    @endphp
                                                                                    @if ($status == 'pending')
                                                                                        <span class="ms-2 badge bg-warning"
                                                                                            title="你已舉報此評論">
                                                                                            <i class="bi bi-flag-fill"></i>
                                                                                            管理員審查中
                                                                                        </span>
                                                                                    @elseif($status == 'resolved')
                                                                                        <span></span>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="reply-content my-1">
                                                                            <p class="mb-1">{{ $reply->content }}</p>
                                                                        </div>

                                                                        <div class="reply-actions">
                                                                            <div class="d-flex flex-wrap gap-1">
                                                                                @can('update', $reply)
                                                                                    <button
                                                                                        class="btn btn-sm btn-light edit-btn"
                                                                                        data-comment-id="{{ $reply->id }}"
                                                                                        data-comment-content="{{ $reply->content }}">
                                                                                        <i class="bi bi-pencil"></i> 編輯
                                                                                    </button>

                                                                                    <!-- 添加顯示/隱藏按鈕 -->
                                                                                    <form
                                                                                        action="{{ route('comments.toggle-visibility', $reply) }}"
                                                                                        method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('PATCH')
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-light">
                                                                                            <i
                                                                                                class="bi {{ $reply->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                                                            隱藏
                                                                                        </button>
                                                                                    </form>
                                                                                @endcan

                                                                                @can('delete', $reply)
                                                                                    <form
                                                                                        action="{{ route('comments.destroy', $reply) }}"
                                                                                        method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-light"
                                                                                            onclick="return confirm('確定要刪除此回覆嗎？')">
                                                                                            <i class="bi bi-trash"></i> 刪除
                                                                                        </button>
                                                                                    </form>
                                                                                @endcan

                                                                                @php
                                                                                    $report = $reply
                                                                                        ->reports()
                                                                                        ->where('user_id', auth()->id())
                                                                                        ->first();
                                                                                @endphp
                                                                                @if ($report)
                                                                                    <form
                                                                                        action="{{ route('comments.unreport', $reply) }}"
                                                                                        method="POST" class="d-inline">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-light"
                                                                                            onclick="return confirm('確定要取消舉報此評論嗎？')">
                                                                                            <i class="bi bi-flag-fill"></i>
                                                                                            取消舉報
                                                                                        </button>
                                                                                    </form>
                                                                                @elseif ($reply->user->id !== auth()->id())
                                                                                    @auth
                                                                                        <button
                                                                                            class="btn btn-sm btn-light report-btn"
                                                                                            data-comment-id="{{ $reply->id }}"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#reportModal-{{ $reply->id }}">
                                                                                            <i class="bi bi-flag"></i> 舉報
                                                                                        </button>

                                                                                        @push('modals')
                                                                                            <!-- 舉報彈窗 -->
                                                                                            <x-report-modal :comment="$reply"
                                                                                                :commentId="$reply->id"
                                                                                                :content="$reply->content" />
                                                                                        @endpush
                                                                                    @endauth
                                                                                @endif
                                                                            </div>

                                                                            <!-- 回覆的編輯表單 (預設隱藏) -->
                                                                            <div class="edit-form mt-2 p-2 rounded bg-white"
                                                                                id="edit-form-{{ $reply->id }}"
                                                                                style="display: none;">
                                                                                <form
                                                                                    action="{{ route('comments.update', $reply) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <div class="mb-2">
                                                                                        <textarea class="form-control form-control-sm ocean-form" name="content" rows="2">{{ $reply->content }}</textarea>
                                                                                    </div>
                                                                                    <div
                                                                                        class="d-flex justify-content-end gap-1">
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-secondary cancel-edit-btn"
                                                                                            data-comment-id="{{ $reply->id }}">取消</button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-primary">更新</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5" data-aos="fade-up">
                                    <i class="bi bi-chat-dots text-muted fs-1"></i>
                                    <p class="mt-2 text-muted">目前還沒有評論，歡迎分享您的看法！</p>
                                    @auth
                                        <button class="btn btn-ocean-outline"
                                            onclick="document.getElementById('content').focus()">
                                            <i class="bi bi-chat-square-text me-1"></i> 發表第一個評論
                                        </button>
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Activity Detail Page Specific Styles */
            .activity-content img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
            }

            .info-card {
                transition: all 0.2s ease;
            }

            .info-card:hover {
                transform: translateY(-3px);
            }

            .icon-circle-sm {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: var(--ocean-foam);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .registration-timeline {
                position: relative;
            }

            .registration-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: #dee2e6;
            }

            .registration-dot.active {
                background-color: var(--ocean-medium);
            }

            .registration-line {
                flex-grow: 1;
                height: 2px;
                background-color: #dee2e6;
            }

            .status-badge {
                font-weight: 500;
                font-size: 0.85rem;
            }

            .status-open {
                background-color: var(--ocean-light);
                color: var(--ocean-deep);
            }

            .status-full {
                background-color: #ff7f50;
                color: white;
            }

            .status-upcoming {
                background-color: #6c757d;
                color: white;
            }

            .status-closed {
                background-color: #dc3545;
                color: white;
            }

            .status-completed {
                background-color: #6c757d;
                color: white;
            }

            .bg-ocean-foam {
                background-color: var(--ocean-foam);
            }

            /* Comment & reply section */
            .comment-item {
                transition: all 0.2s ease;
            }

            .comment-item:hover {
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            }

            .reply {
                transition: all 0.2s ease;
            }

            .reply:hover {
                background-color: #f8f9fa !important;
            }

            /* Role-based avatar styling */
            .avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
            }

            .bg-ocean-foam {
                background-color: var(--ocean-foam);
                color: var(--ocean-deep);
            }

            .avatar-admin {
                background: linear-gradient(135deg, #ff7f50, #ff5e3a);
                color: white;
            }

            .avatar-super {
                background: linear-gradient(135deg, #5e60ce, #6930c3);
                color: white;
            }

            .avatar-member {
                background: linear-gradient(135deg, #0077b6, #00b4d8);
                color: white;
            }

            .avatar-user {
                background: linear-gradient(135deg, #2a9d8f, #57cc99);
                color: white;
            }

            /* Special badge for important roles */
            .avatar.has-badge::after {
                content: '';
                position: absolute;
                width: 12px;
                height: 12px;
                background-color: #ffc107;
                border-radius: 50%;
                bottom: 0;
                right: 0;
                border: 2px solid white;
            }

            @media (max-width: 768px) {
                .sticky-top {
                    position: relative !important;
                    top: 0 !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS for this page if needed
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }

                // Share button functionality
                document.querySelectorAll('.btn[title="複製連結"]').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = window.location.href;
                        navigator.clipboard.writeText(url).then(() => {
                            // Show success tooltip
                            const tooltip = document.createElement('div');
                            tooltip.className =
                                'position-absolute bg-dark text-white p-2 rounded small';
                            tooltip.style.zIndex = 1000;
                            tooltip.style.top = '-40px';
                            tooltip.style.left = '50%';
                            tooltip.style.transform = 'translateX(-50%)';
                            tooltip.textContent = '連結已複製';

                            this.style.position = 'relative';
                            this.appendChild(tooltip);

                            setTimeout(() => tooltip.remove(), 2000);
                        });
                    });
                });

                // 回覆按鈕事件
                document.querySelectorAll('.reply-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.dataset.commentId;
                        document.querySelectorAll('.reply-form').forEach(form => {
                            form.style.display = 'none';
                        });
                        document.querySelectorAll('.edit-form').forEach(form => {
                            form.style.display = 'none';
                        });
                        document.getElementById('reply-form-' + commentId).style.display = 'block';
                    });
                });

                // 取消回覆按鈕事件
                document.querySelectorAll('.cancel-reply-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.dataset.commentId;
                        document.getElementById('reply-form-' + commentId).style.display = 'none';
                    });
                });

                // 編輯按鈕事件
                document.querySelectorAll('.edit-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.dataset.commentId;
                        document.querySelectorAll('.edit-form').forEach(form => {
                            form.style.display = 'none';
                        });
                        document.querySelectorAll('.reply-form').forEach(form => {
                            form.style.display = 'none';
                        });
                        document.getElementById('edit-form-' + commentId).style.display = 'block';
                    });
                });

                // 取消編輯按鈕事件
                document.querySelectorAll('.cancel-edit-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentId = this.dataset.commentId;
                        document.getElementById('edit-form-' + commentId).style.display = 'none';
                    });
                });

                // If there's a hash in URL (for jumping to specific comment)
                if (window.location.hash) {
                    const element = document.querySelector(window.location.hash);
                    if (element) {
                        element.scrollIntoView();
                        element.classList.add('bg-light');
                        setTimeout(() => {
                            element.classList.remove('bg-light');
                        }, 3000);
                    }
                }
            });
        </script>
    @endpush
@endsection
