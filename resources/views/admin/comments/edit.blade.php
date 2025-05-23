@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">編輯評論</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="content" class="form-label">評論內容</label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                          id="content" name="content" rows="5" required>{{ old('content', $comment->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">發布者</label>
                <p class="form-control-static">{{ $comment->user->name }}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label">關聯活動</label>
                <p class="form-control-static">
                    <a href="{{ route('activities.show', $comment->activity) }}" target="_blank">
                        {{ $comment->activity->title }}
                    </a>
                </p>
            </div>
            
            <div class="mb-3">
                <label class="form-label">發布時間</label>
                <p class="form-control-static">{{ $comment->created_at->format('Y/m/d H:i:s') }}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label">狀態</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_pending" 
                               value="pending" {{ $comment->status == 'pending' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_pending">待審核</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_approved" 
                               value="approved" {{ $comment->status == 'approved' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_approved">已核准</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="status_rejected" 
                               value="rejected" {{ $comment->status == 'rejected' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_rejected">已拒絕</label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary me-2">取消</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> 儲存變更
                </button>
            </div>
        </form>
    </div>
</div>
@endsection