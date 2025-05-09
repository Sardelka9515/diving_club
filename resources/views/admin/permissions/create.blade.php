@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">新增權限</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-sm btn-secondary">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">權限名稱</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                <div class="form-text">Slug將自動從名稱生成</div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> 儲存
                </button>
            </div>
        </form>
    </div>
</div>
@endsection