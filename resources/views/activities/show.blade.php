@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1>{{ $activity->title }}</h1>
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <strong>活動時間：</strong>
                    {{ $activity->start_date->format('Y/m/d H:i') }} ~ {{ $activity->end_date->format('Y/m/d H:i') }}
                </div>
                <div class="mb-3">
                    <strong>活動地點：</strong>
                    {{ $activity->location }}
                </div>
                <div class="mb-3">
                    <strong>活動費用：</strong>
                    NT$ {{ number_format($activity->price) }}
                </div>
                <div class="mb-3">
                    <strong>報名時間：</strong>
                    {{ $activity->registration_start->format('Y/m/d H:i') }} ~ {{ $activity->registration_end->format('Y/m/d H:i') }}
                </div>
                <div class="mb-3">
                    <strong>參加人數上限：</strong>
                    {{ $activity->max_participants }} 人
                </div>
                <div class="mb-3">
                    <strong>活動分類：</strong>
                    {{ $activity->category->name }}
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong>活動內容：</strong>
                    <div class="mt-2">
                        {!! $activity->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">報名資訊</div>
            <div class="card-body">
                @php
                    $now = \Carbon\Carbon::now();
                    $canRegister = $now->between($activity->registration_start, $activity->registration_end);
                    $registrationCount = $activity->registrations()->count();
                    $isFull = $activity->max_participants > 0 && $registrationCount >= $activity->max_participants;
                    $userRegistered = auth()->check() ? $activity->registrations()->where('user_id', auth()->id())->exists() : false;
                @endphp
                
                <div class="mb-3">
                    <div class="progress">
                        @if($activity->max_participants > 0)
                            @php
                                $percentage = min(100, ($registrationCount / $activity->max_participants) * 100);
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                {{ $registrationCount }}/{{ $activity->max_participants }}
                            </div>
                        @else
                            <div class="progress-bar" role="progressbar" style="width: 100%">
                                {{ $registrationCount }} 人已報名
                            </div>
                        @endif
                    </div>
                </div>
                
                @if(!auth()->check())
                    <div class="alert alert-info">請先登入才能報名活動</div>
                    <a href="{{ route('login') }}" class="btn btn-primary">登入</a>
                @elseif($userRegistered)
                    <div class="alert alert-success">您已經報名此活動</div>
                    <form action="{{ route('activities.unregister', $activity) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('確定要取消報名嗎？')">
                            取消報名
                        </button>
                    </form>
                @elseif(!$canRegister)
                    <div class="alert alert-warning">目前不在報名時間內</div>
                    <button class="btn btn-secondary" disabled>報名</button>
                @elseif($isFull)
                    <div class="alert alert-warning">報名人數已滿</div>
                    <button class="btn btn-secondary" disabled>報名</button>
                @else
                    <form action="{{ route('activities.register', $activity) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">立即報名</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection