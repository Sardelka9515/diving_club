@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="card">
    <div class="card-header">
        <h1>{{ $announcement->title }}</h1>
        <div class="text-muted">
            發布時間：{{ $announcement->published_at->format('Y/m/d H:i') }}
            <span class="mx-2">|</span>
            發布者：{{ $announcement->user->name }}
            @if($announcement->is_pinned)
            <span class="badge bg-danger ms-2">置頂</span>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="announcement-content">
            {!! $announcement->content !!}
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> 返回公告列表
        </a>
    </div>
</div>
@endsection