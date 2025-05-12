@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">編輯公告</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">公告標題 <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                       id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">公告內容 <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" 
                          id="content" name="content" rows="10" required>{{ old('content', strip_tags($announcement->content)) }}</textarea>
                <div class="form-text">請輸入純文字內容，系統會自動處理格式。</div>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" 
                               {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_published">
                            立即發布
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_pinned" name="is_pinned" value="1" 
                               {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_pinned">
                            置頂公告
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> 更新公告
                </button>
            </div>
        </form>
    </div>
</div>
@endsection