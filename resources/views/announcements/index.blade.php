@extends('layouts.app')

@section('title', '公告列表')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>公告列表</h1>
        
        @if($pinnedAnnouncements->count() > 0)
        <div class="mb-4">
            <h5>置頂公告</h5>
            <div class="list-group">
                @foreach($pinnedAnnouncements as $announcement)
                <a href="{{ route('announcements.show', $announcement) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">
                            <i class="bi bi-pin-angle-fill text-danger"></i>
                            {{ $announcement->title }}
                        </h5>
                        <small>{{ $announcement->published_at->format('Y/m/d') }}</small>
                    </div>
                    <p class="mb-1">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                    <small>發布者: {{ $announcement->user->name }}</small>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="list-group">
            @forelse($announcements as $announcement)
            <a href="{{ route('announcements.show', $announcement) }}" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                    <small>{{ $announcement->published_at->format('Y/m/d') }}</small>
                </div>
                <p class="mb-1">{{ Str::limit(strip_tags($announcement->content), 150) }}</p>
                <small>發布者: {{ $announcement->user->name }}</small>
            </a>
            @empty
            <div class="alert alert-info">
                目前沒有公告
            </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection