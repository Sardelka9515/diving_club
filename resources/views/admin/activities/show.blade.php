@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">活動詳情</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
        <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> 編輯
        </a>
        <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除此活動嗎？')">
                <i class="bi bi-trash"></i> 刪除
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ $activity->title }}
                    @if($activity->is_published)
                        <span class="badge bg-success ms-2">已發布</span>
                    @else
                        <span class="badge bg-warning ms-2">草稿</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted">活動描述</h6>
                <p class="mb-3">{{ $activity->description }}</p>
                
                <h6 class="text-muted">活動內容</h6>
                <div class="border rounded p-3 bg-light">
                    {!! $activity->content !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">活動資訊</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5"><strong>類別：</strong></div>
                    <div class="col-sm-7">
                        @if($activity->category)
                            <span class="badge bg-secondary">{{ $activity->category->name }}</span>
                        @else
                            <span class="text-muted">未分類</span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-5"><strong>地點：</strong></div>
                    <div class="col-sm-7">{{ $activity->location }}</div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-5"><strong>費用：</strong></div>
                    <div class="col-sm-7">
                        <span class="text-success fw-bold">NT$ {{ number_format($activity->price) }}</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-5"><strong>人數限制：</strong></div>
                    <div class="col-sm-7">
                        @if($activity->max_participants > 0)
                            {{ $activity->max_participants }} 人
                        @else
                            <span class="text-muted">無限制</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">時間資訊</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">活動時間</small>
                    <div class="fw-bold">
                        {{ $activity->start_date->format('Y年m月d日 H:i') }}
                        <br>
                        至 {{ $activity->end_date->format('Y年m月d日 H:i') }}
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">報名時間</small>
                    <div>
                        {{ $activity->registration_start->format('Y年m月d日 H:i') }}
                        <br>
                        至 {{ $activity->registration_end->format('Y年m月d日 H:i') }}
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted">創建時間</small>
                    <div>{{ $activity->created_at->format('Y年m月d日 H:i') }}</div>
                </div>
                <div>
                    <small class="text-muted">最後更新</small>
                    <div>{{ $activity->updated_at->format('Y年m月d日 H:i') }}</div>
                </div>
            </div>
        </div>

        @if($activity->registrations->count() > 0)
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">報名狀況</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>總報名人數</span>
                        <span class="fw-bold">{{ $activity->registrations->count() }}</span>
                    </div>
                    @if($activity->max_participants > 0)
                        <div class="progress mt-2">
                            @php
                                $percentage = min(100, ($activity->registrations->count() / $activity->max_participants) * 100);
                            @endphp
                            <div class="progress-bar bg-{{ $percentage >= 100 ? 'danger' : ($percentage >= 80 ? 'warning' : 'success') }}" 
                                 role="progressbar" style="width: {{ $percentage }}%">
                                {{ round($percentage) }}%
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="row text-center">
                    <div class="col">
                        <div class="text-success fw-bold">{{ $activity->registrations->where('status', 'approved')->count() }}</div>
                        <small class="text-muted">已確認</small>
                    </div>
                    <div class="col">
                        <div class="text-warning fw-bold">{{ $activity->registrations->where('status', 'pending')->count() }}</div>
                        <small class="text-muted">待審核</small>
                    </div>
                    <div class="col">
                        <div class="text-danger fw-bold">{{ $activity->registrations->where('status', 'rejected')->count() }}</div>
                        <small class="text-muted">已拒絕</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection