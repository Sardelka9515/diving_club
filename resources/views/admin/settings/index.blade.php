@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">系統設定</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form action="{{ route('admin.settings.clear-cache') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="bi bi-arrow-clockwise"></i> 清除系統緩存
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            <h4 class="mb-3">基本設定</h4>
            
            <div class="mb-3">
                <label for="site_name" class="form-label">網站名稱</label>
                <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']->value ?? '潛水社') }}" required>
                @error('site_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="site_description" class="form-label">網站描述</label>
                <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description" rows="2">{{ old('site_description', $settings['site_description']->value ?? '探索海洋世界的最佳夥伴') }}</textarea>
                @error('site_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-4">
            <h4 class="mb-3">聯絡資訊</h4>
            
            <div class="mb-3">
                <label for="contact_email" class="form-label">聯絡郵箱</label>
                <input type="email" class="form-control @error('contact_email') is-invalid @enderror" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']->value ?? 'contact@divingclub.com') }}">
                @error('contact_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-4">
            <h4 class="mb-3">頁面設定</h4>
            
            <div class="mb-3">
                <label for="footer_text" class="form-label">頁腳文字</label>
                <input type="text" class="form-control @error('footer_text') is-invalid @enderror" id="footer_text" name="footer_text" value="{{ old('footer_text', $settings['footer_text']->value ?? '© ' . date('Y') . ' 潛水社. 保留所有權利。') }}">
                @error('footer_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <hr class="my-4">
            <h4 class="mb-3">系統設定</h4>
            
            <div class="mb-3 form-check form-switch">
                <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']->value ?? '0') == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="maintenance_mode">啟用維護模式</label>
                <div class="form-text">啟用後，只有管理員可以訪問網站。</div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> 儲存設定
                </button>
            </div>
        </form>
    </div>
</div>
@endsection