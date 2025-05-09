@extends('layouts.app')

@section('title', '我的活動')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">個人資料</a>
            <a href="{{ route('member.activities') }}" class="list-group-item list-group-item-action active">我的活動</a>
        </div>
    </div>
    <div class="col-md-9">
        <h1>已報名活動</h1>
        
        <div class="list-group">
            @forelse($registrations as $registration)
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $registration->activity->title }}</h5>
                    <span class="badge bg-{{ $registration->status == 'pending' ? 'warning' : ($registration->status == 'approved' ? 'success' : 'danger') }}">
                        {{ $registration->status == 'pending' ? '審核中' : ($registration->status == 'approved' ? '已核准' : '已拒絕') }}
                    </span>
                </div>
                <p class="mb-1">
                    <strong>活動時間：</strong>
                    {{ $registration->activity->start_date->format('Y/m/d H:i') }} ~ {{ $registration->activity->end_date->format('Y/m/d H:i') }}
                </p>
                <p class="mb-1">
                    <strong>活動地點：</strong>
                    {{ $registration->activity->location }}
                </p>
                <p class="mb-1">
                    <strong>報名時間：</strong>
                    {{ $registration->created_at->format('Y/m/d H:i') }}
                </p>
                <div class="mt-2">
                    <a href="{{ route('activities.show', $registration->activity) }}" class="btn btn-sm btn-primary">
                        查看活動
                    </a>
                    <form action="{{ route('activities.unregister', $registration->activity) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要取消報名嗎？')">
                            取消報名
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                您尚未報名任何活動
            </div>
            @endforelse
        </div>
        
        <div class="mt-4">
            {{ $registrations->links() }}
        </div>
    </div>
</div>
@endsection