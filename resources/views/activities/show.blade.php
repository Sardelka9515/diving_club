@extends('layouts.app')

@section('title', $activity->title)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $activity->title }}</h1>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <strong>活動時間：</strong>
                        {{ $activity->start_date->format('Y/m/d H:i') }} ~ {{ $activity->end_date->format('Y/m/d H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>活動地點：</strong>
                        {{ $activity->location }}
                    </div>
                    <div class="mb-3">
                        <strong>活動費用：</strong>
                        NT$ {{ number_format($activity->price) }}
                    </div>
                    <div class="mb-3">
                        <strong>報名時間：</strong>
                        {{ $activity->registration_start->format('Y/m/d H:i') }} ~
                        {{ $activity->registration_end->format('Y/m/d H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>參加人數上限：</strong>
                        {{ $activity->max_participants }} 人
                    </div>
                    <div class="mb-3">
                        <strong>活動分類：</strong>
                        {{ $activity->category->name }}
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong>活動內容：</strong>
                        <div class="mt-2">
                            {!! $activity->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">報名資訊</div>
                <div class="card-body">
                    @php
                        $now = \Carbon\Carbon::now();
                        $canRegister = $now->between($activity->registration_start, $activity->registration_end);
                        $registrationCount = $activity->registrations()->count();
                        $isFull = $activity->max_participants > 0 && $registrationCount >= $activity->max_participants;
                        $userRegistered = auth()->check()
                            ? $activity
                                ->registrations()
                                ->where('user_id', auth()->id())
                                ->exists()
                            : false;
                    @endphp

                    <div class="mb-3">
                        <div class="progress">
                            @if ($activity->max_participants > 0)
                                @php
                                    $percentage = min(100, ($registrationCount / $activity->max_participants) * 100);
                                @endphp
                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                    {{ $registrationCount }}/{{ $activity->max_participants }}
                                </div>
                            @else
                                <div class="progress-bar" role="progressbar" style="width: 100%">
                                    {{ $registrationCount }} 人已報名
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (!auth()->check())
                        <div class="alert alert-info">請先登入才能報名活動</div>
                        <a href="{{ route('login') }}" class="btn btn-primary">登入</a>
                    @elseif($userRegistered)
                        <div class="alert alert-success">您已經報名此活動</div>
                        <form action="{{ route('activities.unregister', $activity) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('確定要取消報名嗎？')">
                                取消報名
                            </button>
                        </form>
                    @elseif(!$canRegister)
                        <div class="alert alert-warning">目前不在報名時間內</div>
                        <button class="btn btn-secondary" disabled>報名</button>
                    @elseif($isFull)
                        <div class="alert alert-warning">報名人數已滿</div>
                        <button class="btn btn-secondary" disabled>報名</button>
                    @else
                        <form action="{{ route('activities.register', $activity) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">立即報名</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <!-- Activity Detail Comment Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h4>活動討論區</h4>
            </div>
            <div class="card-body">
                @auth
                    <form action="{{ route('comments.store', $activity) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="content" class="form-label">發表評論</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3"
                                placeholder="分享您對此活動的看法..."></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-chat-dots me-1"></i> 送出評論
                            </button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info">
                        請<a href="{{ route('login') }}" class="alert-link">登入</a>後參與討論。
                    </div>
                @endauth

                <hr class="my-4">

                <!-- 評論列表 -->
                <div class="comments-list">
                    @if ($activity->comments()->approved()->whereNull('parent_id')->count() > 0)
                        @foreach ($activity->comments()->approved()->whereNull('parent_id')->latest()->get() as $comment)
                            <div class="comment-item mb-4" id="comment-{{ $comment->id }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 45px; height: 45px;">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="comment-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="comment-content my-2">
                                            <p class="mb-1">{{ $comment->content }}</p>
                                        </div>

                                        <div class="comment-actions">
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-light me-2 reply-btn"
                                                    data-comment-id="{{ $comment->id }}">
                                                    <i class="bi bi-reply"></i> 回覆
                                                </button>

                                                @can('update', $comment)
                                                    <button class="btn btn-sm btn-light me-2 edit-btn"
                                                        data-comment-id="{{ $comment->id }}"
                                                        data-comment-content="{{ $comment->content }}">
                                                        <i class="bi bi-pencil"></i> 編輯
                                                    </button>
                                                @endcan

                                                @can('delete', $comment)
                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light me-2"
                                                            onclick="return confirm('確定要刪除此評論嗎？')">
                                                            <i class="bi bi-trash"></i> 刪除
                                                        </button>
                                                    </form>
                                                @endcan

                                                @auth
                                                    <button class="btn btn-sm btn-light report-btn"
                                                        data-comment-id="{{ $comment->id }}" data-bs-toggle="modal"
                                                        data-bs-target="#reportModal-{{ $comment->id }}">
                                                        <i class="bi bi-flag"></i> 舉報
                                                    </button>

                                                    <!-- 舉報彈窗 -->
                                                    <div class="modal fade" id="reportModal-{{ $comment->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">舉報不當評論</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('comments.report', $comment) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">評論內容</label>
                                                                            <p class="border p-2 bg-light">
                                                                                {{ $comment->content }}</p>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="reason-{{ $comment->id }}"
                                                                                class="form-label">舉報原因</label>
                                                                            <select class="form-select"
                                                                                id="reason-{{ $comment->id }}"
                                                                                name="reason" required>
                                                                                <option value="">請選擇舉報原因...</option>
                                                                                <option value="spam">垃圾/廣告訊息</option>
                                                                                <option value="offensive">冒犯性內容</option>
                                                                                <option value="inappropriate">不恰當內容</option>
                                                                                <option value="other">其他原因</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label for="details-{{ $comment->id }}"
                                                                                class="form-label">詳細說明 (選填)</label>
                                                                            <textarea class="form-control" id="details-{{ $comment->id }}" name="details" rows="3"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">取消</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">提交舉報</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endauth
                                            </div>
                                        </div>

                                        <!-- 回覆列表 -->
                                        @if ($comment->replies()->approved()->count() > 0)
                                            <div class="replies mt-3">
                                                @foreach ($comment->replies()->approved()->get() as $reply)
                                                    <div class="reply border-start border-2 ps-3 py-2 mb-2"
                                                        id="comment-{{ $reply->id }}">
                                                        <div class="d-flex">
                                                            <div class="flex-shrink-0">
                                                                <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                                    style="width: 35px; height: 35px;">
                                                                    <i class="bi bi-person text-secondary"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2">
                                                                <div
                                                                    class="reply-header d-flex justify-content-between align-items-center">
                                                                    <h6 class="mb-0 small">{{ $reply->user->name }}</h6>
                                                                    <small
                                                                        class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                                                </div>
                                                                <div class="reply-content my-1">
                                                                    <p class="mb-1 small">{{ $reply->content }}</p>
                                                                </div>

                                                                <div class="reply-actions">
                                                                    <div class="d-flex">
                                                                        @can('update', $reply)
                                                                            <button class="btn btn-sm btn-light me-2 edit-btn"
                                                                                data-comment-id="{{ $reply->id }}"
                                                                                data-comment-content="{{ $reply->content }}">
                                                                                <i class="bi bi-pencil"></i> 編輯
                                                                            </button>
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
                                                                    </div>

                                                                    <!-- 回覆的編輯表單 (預設隱藏) -->
                                                                    <div class="edit-form mt-2"
                                                                        id="edit-form-{{ $reply->id }}"
                                                                        style="display: none;">
                                                                        <form
                                                                            action="{{ route('comments.update', $reply) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="mb-2">
                                                                                <textarea class="form-control form-control-sm" name="content" rows="2">{{ $reply->content }}</textarea>
                                                                            </div>
                                                                            <div class="d-flex justify-content-end">
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-secondary me-2 cancel-edit-btn"
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
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots text-muted fs-1"></i>
                            <p class="mt-2 text-muted">目前還沒有評論，歡迎分享您的看法！</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
            });
        </script>
    @endpush

@endsection
