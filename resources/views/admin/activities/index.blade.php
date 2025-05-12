@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">活動管理</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> 新增活動
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>標題</th>
                        <th>類別</th>
                        <th>活動時間</th>
                        <th>報名時間</th>
                        <th>人數限制</th>
                        <th>價格</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->id }}</td>
                        <td>
                            <strong>{{ $activity->title }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($activity->description, 50) }}</small>
                        </td>
                        <td>
                            @if($activity->category)
                                <span class="badge bg-secondary">{{ $activity->category->name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                {{ $activity->start_date->format('Y/m/d H:i') }}
                                <br>
                                至 {{ $activity->end_date->format('Y/m/d H:i') }}
                            </small>
                        </td>
                        <td>
                            <small>
                                {{ $activity->registration_start->format('Y/m/d H:i') }}
                                <br>
                                至 {{ $activity->registration_end->format('Y/m/d H:i') }}
                            </small>
                        </td>
                        <td>{{ $activity->max_participants ?: '無限制' }}</td>
                        <td>NT$ {{ number_format($activity->price) }}</td>
                        <td>
                            @if($activity->is_published)
                                <span class="badge bg-success">已發布</span>
                            @else
                                <span class="badge bg-warning">草稿</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除此活動嗎？')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">目前沒有活動</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection