@extends('layouts.app')

@section('title', '活動列表')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header">分類篩選</div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('activities.index') }}" class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                        全部活動
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('activities.index', ['category' => $category->slug]) }}" 
                       class="list-group-item list-group-item-action {{ request('category') == $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <h1>活動列表</h1>
        
        <div class="row g-4">
            @forelse($activities as $activity)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $activity->title }}</h5>
                        <p class="card-text text-muted">
                            <small>
                                <i class="bi bi-calendar"></i> {{ $activity->start_date->format('Y/m/d H:i') }}
                                <br>
                                <i class="bi bi-geo-alt"></i> {{ $activity->location }}
                            </small>
                        </p>
                        <p class="card-text">{{ Str::limit($activity->description, 100) }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('activities.show', $activity) }}" class="btn btn-primary">查看詳情</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    目前沒有活動
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection