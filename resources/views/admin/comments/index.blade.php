@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">評論管理</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <!-- 過濾與搜尋 -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.comments.index', ['status' => 'all']) }}"
                            class="btn btn-{{ $status == 'all' ? 'primary' : 'outline-primary' }}">
                            全部
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'published']) }}"
                            class="btn btn-{{ $status == 'published' ? 'success' : 'outline-success' }}">
                            已發布
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'hidden']) }}"
                            class="btn btn-{{ $status == 'hidden' ? 'secondary' : 'outline-secondary' }}">
                            已隱藏
                        </a>
                        <a href="{{ route('admin.comments.index', ['status' => 'reported']) }}"
                            class="btn btn-{{ $status == 'reported' ? 'warning' : 'outline-warning' }}">
                            被檢舉
                        </a>
                        {{-- <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
                            class="btn btn-{{ $status == 'pending' ? 'info' : 'outline-info' }}">
                            待審核
                        </a> --}}
                        <a href="{{ route('admin.comments.index', ['recent' => 'true']) }}"
                            class="btn btn-{{ request('recent') == 'true' ? 'dark' : 'outline-dark' }}">
                            最近 24 小時
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <form class="d-flex" action="{{ route('admin.comments.index') }}" method="GET">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input class="form-control me-2" type="search" name="search" placeholder="搜尋評論..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-success" type="submit">搜尋</button>
                    </form>
                </div>
            </div>

            <!-- 統計資訊 -->
            <div class="row mb-4">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">總評論</h5>
                            <p class="card-text h3">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">已發布</h5>
                            <p class="card-text h3">{{ $stats['published'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center bg-secondary text-white">
                        <div class="card-body">
                            <h5 class="card-title">已隱藏</h5>
                            <p class="card-text h3">{{ $stats['hidden'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">被檢舉</h5>
                            <p class="card-text h3">{{ $stats['reported'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center bg-info">
                        <div class="card-body">
                            <h5 class="card-title">待審核</h5>
                            <p class="card-text h3">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                    <div class="card text-center bg-black text-white">
                        <div class="card-body">
                            <h5 class="card-title">今日新增</h5>
                            <p class="card-text h3">{{ $stats['today'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 評論列表 -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>內容</th>
                            <th>用戶</th>
                            <th>活動</th>
                            <th>狀態</th>
                            <th>日期</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td style="max-width: 300px;">
                                    <div class="text-truncate">{{ $comment->content }}</div>
                                    @if ($comment->parent_id)
                                        <div class="badge bg-secondary mt-1">回覆評論 #{{ $comment->parent_id }}</div>
                                    @endif
                                    @if ($comment->reports_count > 0)
                                        <div class="badge bg-danger mt-1">
                                            <i class="bi bi-flag-fill"></i> {{ $comment->reports_count }} 次檢舉
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $comment->user_id) }}" data-bs-toggle="tooltip" title="查看用戶詳情">
                                        {{ $comment->user->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('activities.show', $comment->activity) }}" target="_blank">
                                        {{ Str::limit($comment->activity->title, 20) }}
                                    </a>
                                </td>
                                <td>
                                    @if ($comment->is_reported)
                                        <span class="badge bg-danger">被檢舉</span>
                                    @elseif(!$comment->is_visible)
                                        <span class="badge bg-secondary">已隱藏</span>
                                    @elseif($comment->status == 'pending')
                                        <span class="badge bg-info">待審核</span>
                                    @elseif($comment->status == 'approved')
                                        <span class="badge bg-success">已發布</span>
                                    @else
                                        <span class="badge bg-danger">已拒絕</span>
                                    @endif
                                </td>
                                <td>{{ $comment->created_at->format('Y/m/d H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- 審核按鈕 -->
                                        @if ($comment->status == 'pending')
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="核准">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.comments.reject', $comment) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="拒絕">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- 顯示/隱藏按鈕 -->
                                        <form action="{{ route('admin.comments.toggle-visibility', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $comment->is_visible ? 'btn-outline-secondary' : 'btn-outline-success' }}" data-bs-toggle="tooltip" 
                                                title="{{ $comment->is_visible ? '隱藏評論' : '顯示評論' }}">
                                                <i class="bi {{ $comment->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- 編輯按鈕 -->
                                        <a href="{{ route('admin.comments.edit', $comment) }}"
                                            class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="編輯">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- 查看檢舉 -->
                                        @if ($comment->reports_count > 0)
                                            <a href="{{ route('admin.reports.index', ['comment_id' => $comment->id]) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="查看檢舉">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </a>
                                        @endif

                                        <!-- 刪除按鈕 -->
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="永久刪除"
                                                onclick="return confirm('確定要永久刪除此評論嗎？此操作無法復原。')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">沒有找到評論</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-center mt-4">
                {{ $comments->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // 啟用所有工具提示
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
@endsection