@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">公告詳情</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> 編輯
        </a>
        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除此公告嗎？')">
                <i class="bi bi-trash"></i> 刪除
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ $announcement->title }}</h4>
                    <div>
                        @if($announcement->is_pinned)
                            <span class="badge bg-danger me-2">置頂</span>
                        @endif
                        @if($announcement->is_published)
                            <span class="badge bg-success">已發布</span>
                        @else
                            <span class="badge bg-warning">草稿</span>
                        @endif
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        發布者：{{ $announcement->user->name }}
                        @if($announcement->published_at)
                            | 發布時間：{{ $announcement->published_at->format('Y年m月d日 H:i') }}
                        @endif
                    </small>
                </div>
            </div>
            <div class="card-body">
                <div class="announcement-content">
                    {!! $announcement->content !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">公告資訊</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">狀態</small>
                    <div>
                        @if($announcement->is_published)
                            <span class="badge bg-success">已發布</span>
                        @else
                            <span class="badge bg-warning">草稿</span>
                        @endif
                        @if($announcement->is_pinned)
                            <span class="badge bg-danger ms-1">置頂</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">發布者</small>
                    <div class="fw-bold">{{ $announcement->user->name }}</div>
                </div>

                @if($announcement->published_at)
                <div class="mb-3">
                    <small class="text-muted">發布時間</small>
                    <div>{{ $announcement->published_at->format('Y年m月d日 H:i') }}</div>
                </div>
                @endif

                <div class="mb-3">
                    <small class="text-muted">創建時間</small>
                    <div>{{ $announcement->created_at->format('Y年m月d日 H:i') }}</div>
                </div>

                <div>
                    <small class="text-muted">最後更新</small>
                    <div>{{ $announcement->updated_at->format('Y年m月d日 H:i') }}</div>
                </div>
            </div>
        </div>

        @if($announcement->is_published)
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">前台連結</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-eye"></i> 前台預覽
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection