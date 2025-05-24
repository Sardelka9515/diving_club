@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">評論舉報管理</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 統計卡片 -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">總舉報數</h5>
                    <p class="card-text h3">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning">
                <div class="card-body">
                    <h5 class="card-title">待處理</h5>
                    <p class="card-text h3">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">已處理</h5>
                    <p class="card-text h3">{{ $stats['resolved'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-dark text-white">
                <div class="card-body">
                    <h5 class="card-title">今日舉報</h5>
                    <p class="card-text h3">{{ $stats['today'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- 過濾與搜尋 -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.reports.index', ['status' => 'all']) }}"
                            class="btn btn-{{ $status == 'all' ? 'primary' : 'outline-primary' }}">
                            全部
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
                            class="btn btn-{{ $status == 'pending' ? 'warning' : 'outline-warning' }}">
                            待處理
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}"
                            class="btn btn-{{ $status == 'resolved' ? 'success' : 'outline-success' }}">
                            已處理
                        </a>
                        <a href="{{ route('admin.reports.index', ['status' => 'auto_resolved']) }}"
                            class="btn btn-{{ $status == 'auto_resolved' ? 'info' : 'outline-info' }}">
                            自動處理
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <form class="d-flex" action="{{ route('admin.reports.index') }}" method="GET">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input class="form-control me-2" type="search" name="search" placeholder="搜尋舉報..."
                            value="{{ request('search') }}">
                        <button class="btn btn-outline-success" type="submit">搜尋</button>
                    </form>
                </div>
            </div>

            <!-- 舉報列表 -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>舉報原因</th>
                            <th>評論內容</th>
                            <th>舉報者</th>
                            <th>評論作者</th>
                            <th>評論狀態</th>
                            <th>舉報狀態</th>
                            <th>日期</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr class="{{ $report->status == 'pending' ? 'table-warning' : '' }}">
                                <td>{{ $report->id }}</td>
                                <td>
                                    <span
                                        class="badge {{ $report->reason == 'harassment' || $report->reason == 'violence_hate' ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ [
                                            'dislike' => '不喜歡',
                                            'harassment' => '騷擾',
                                            'self_harm' => '自我傷害',
                                            'violence_hate' => '暴力或仇恨言論',
                                            'regulated_goods' => '受管制商品',
                                            'nudity' => '裸露或色情內容',
                                            'fraud_spam' => '詐騙或垃圾訊息',
                                            'false_info' => '虛假資訊',
                                            'other' => '其他原因',
                                        ][$report->reason] ?? $report->reason }}
                                    </span>
                                    @if ($report->details)
                                        <button class="btn btn-sm btn-link" data-bs-toggle="modal"
                                            data-bs-target="#detailsModal-{{ $report->id }}">
                                            查看詳情
                                        </button>

                                        <!-- 詳情彈窗 -->
                                        <div class="modal fade" id="detailsModal-{{ $report->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">舉報詳情</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $report->details }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">關閉</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td style="max-width: 200px;">
                                    @if ($report->comment)
                                        <div class="text-truncate">{{ $report->comment->content }}</div>
                                        @if ($report->comment->parent_id)
                                            <div class="badge bg-info mt-1">回覆評論 #{{ $report->comment->parent_id }}</div>
                                        @endif
                                    @else
                                        <div class="text-danger"><i>已刪除的評論</i></div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $report->user_id) }}" data-bs-toggle="tooltip"
                                        title="查看用戶詳情">
                                        {{ $report->user->name }}
                                    </a>
                                </td>
                                <td>
                                    @if ($report->comment && $report->comment->user)
                                        <a href="{{ route('admin.users.show', $report->comment->user_id) }}"
                                            data-bs-toggle="tooltip" title="查看用戶詳情">
                                            {{ $report->comment->user->name }}
                                        </a>
                                    @else
                                        <span class="text-danger"><i>用戶已刪除</i></span>
                                    @endif
                                </td>
                                <td>
                                    @if ($report->comment)
                                        @if (!$report->comment->is_visible)
                                            <span class="badge bg-secondary">已隱藏</span>
                                        @elseif($report->comment->is_reported)
                                            <span class="badge bg-danger">被檢舉</span>
                                        @else
                                            <span class="badge bg-success">可見</span>
                                        @endif
                                    @else
                                        <span class="badge bg-dark">已刪除</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($report->status == 'pending')
                                        <span class="badge bg-warning">待處理</span>
                                    @elseif($report->status == 'resolved')
                                        <span class="badge bg-success">已處理</span>
                                    @elseif($report->status == 'auto_resolved')
                                        <span class="badge bg-info">自動處理</span>
                                        <small class="d-block text-muted">(使用者已隱藏評論)</small>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $report->created_at->format('Y/m/d') }}</div>
                                    <small class="text-muted">{{ $report->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if ($report->comment)
                                            <!-- 查看活動連結 -->
                                            <a href="{{ route('activities.show', $report->comment->activity_id) }}#comment-{{ $report->comment->id }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                title="在活動中查看" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- 編輯評論按鈕 -->
                                            <a href="{{ route('admin.comments.edit', $report->comment) }}"
                                                class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="編輯評論">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- 顯示/隱藏評論按鈕 -->
                                            <form
                                                action="{{ route('admin.comments.toggle-visibility', $report->comment) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-sm {{ $report->comment->is_visible ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $report->comment->is_visible ? '隱藏評論' : '顯示評論' }}">
                                                    <i
                                                        class="bi {{ $report->comment->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled data-bs-toggle="tooltip"
                                                title="評論已刪除">
                                                <i class="bi bi-trash-check"></i>
                                            </button>
                                        @endif

                                        <!-- 設置舉報狀態 -->
                                        @if ($report->status == 'pending')
                                        <!-- 清除舉報標記 -->
                                            <form action="{{ route('admin.reports.resolve', $report) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="clear_reported" value="1">
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    data-bs-toggle="tooltip" title="處理舉報">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @elseif($report->status == 'resolved' || $report->status == 'auto_resolved')
                                            <form action="{{ route('admin.reports.reopen', $report) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-warning"
                                                    data-bs-toggle="tooltip" title="重新開啟舉報">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- 刪除評論按鈕 -->
                                        @if ($report->comment)
                                            <form action="{{ route('admin.comments.destroy', $report->comment) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" title="刪除評論"
                                                    onclick="return confirm('確定要刪除此評論嗎？此操作無法復原。')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">沒有找到舉報</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- 分頁 -->
            <div class="d-flex justify-content-center mt-4">
                {{ $reports->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // 啟用所有工具提示
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
