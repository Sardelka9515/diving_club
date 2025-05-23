@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">評論管理</h1>
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
                    <a href="{{ route('admin.comments.index', ['status' => 'all']) }}" 
                       class="btn btn-{{ $status == 'all' ? 'primary' : 'outline-primary' }}">
                        全部
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" 
                       class="btn btn-{{ $status == 'pending' ? 'warning' : 'outline-warning' }}">
                        待審核
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}" 
                       class="btn btn-{{ $status == 'approved' ? 'success' : 'outline-success' }}">
                        已核准
                    </a>
                    <a href="{{ route('admin.comments.index', ['status' => 'rejected']) }}" 
                       class="btn btn-{{ $status == 'rejected' ? 'danger' : 'outline-danger' }}">
                        已拒絕
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
                            @if($comment->parent_id)
                                <div class="badge bg-secondary mt-1">回覆評論 #{{ $comment->parent_id }}</div>
                            @endif
                        </td>
                        <td>{{ $comment->user->name }}</td>
                        <td>
                            <a href="{{ route('activities.show', $comment->activity) }}" target="_blank">
                                {{ Str::limit($comment->activity->title, 20) }}
                            </a>
                        </td>
                        <td>
                            @if($comment->status == 'pending')
                                <span class="badge bg-warning">待審核</span>
                            @elseif($comment->status == 'approved')
                                <span class="badge bg-success">已核准</span>
                            @else
                                <span class="badge bg-danger">已拒絕</span>
                            @endif
                        </td>
                        <td>{{ $comment->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($comment->status == 'pending')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @elseif($comment->status == 'rejected')
                                    <form action="{{ route('admin.comments.approve', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                @elseif($comment->status == 'approved')
                                    <form action="{{ route('admin.comments.reject', $comment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('admin.comments.edit', $comment) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除此評論嗎？')">
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
@endsection