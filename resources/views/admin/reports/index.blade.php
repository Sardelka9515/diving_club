<!-- admin/reports/index.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">評論舉報管理</h1>
</div>

@if(session('success'))
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
                    <a href="{{ route('admin.reports.index', ['status' => 'rejected']) }}" 
                       class="btn btn-{{ $status == 'rejected' ? 'danger' : 'outline-danger' }}">
                        已駁回
                    </a>
                </div>
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
                        <th>狀態</th>
                        <th>日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>{{ $report->id }}</td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ [
                                    'spam' => '垃圾/廣告訊息',
                                    'offensive' => '冒犯性內容',
                                    'inappropriate' => '不恰當內容',
                                    'other' => '其他原因'
                                ][$report->reason] ?? $report->reason }}
                            </span>
                            @if($report->details)
                                <button class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ $report->id }}">
                                    查看詳情
                                </button>
                                
                                <!-- 詳情彈窗 -->
                                <div class="modal fade" id="detailsModal-{{ $report->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">舉報詳情</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ $report->details }}</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td style="max-width: 200px;">
                            <div class="text-truncate">{{ $report->comment->content }}</div>
                        </td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ $report->comment->user->name }}</td>
                        <td>
                            @if($report->status == 'pending')
                                <span class="badge bg-warning">待處理</span>
                            @elseif($report->status == 'resolved')
                                <span class="badge bg-success">已處理</span>
                            @else
                                <span class="badge bg-danger">已駁回</span>
                            @endif
                        </td>
                        <td>{{ $report->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.comments.edit', $report->comment) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> 編輯評論
                                </a>
                                
                                @if($report->status == 'pending')
                                    <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i> 標記為已處理
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.reports.reject', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i> 駁回舉報
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.comments.destroy', $report->comment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除此評論嗎？')">
                                        <i class="bi bi-trash"></i> 刪除評論
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">沒有找到舉報</td>
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
@endsection