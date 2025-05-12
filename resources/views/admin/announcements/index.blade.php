@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">公告管理</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> 新增公告
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
                        <th>發布者</th>
                        <th>發布時間</th>
                        <th>狀態</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $announcement->id }}</td>
                        <td>
                            <strong>{{ $announcement->title }}</strong>
                            @if($announcement->is_pinned)
                                <span class="badge bg-danger ms-2">置頂</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ Str::limit(strip_tags($announcement->content), 100) }}</small>
                        </td>
                        <td>{{ $announcement->user->name }}</td>
                        <td>{{ $announcement->published_at ? $announcement->published_at->format('Y/m/d H:i') : '-' }}</td>
                        <td>
                            @if($announcement->is_published)
                                <span class="badge bg-success">已發布</span>
                            @else
                                <span class="badge bg-warning">草稿</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.announcements.show', $announcement) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                               </a>
                               <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="d-inline">
                                   @csrf
                                   @method('DELETE')
                                   <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除此公告嗎？')">
                                       <i class="bi bi-trash"></i>
                                   </button>
                               </form>
                           </div>
                       </td>
                   </tr>
                   @empty
                   <tr>
                       <td colspan="6" class="text-center py-4">目前沒有公告</td>
                   </tr>
                   @endforelse
               </tbody>
           </table>
       </div>

       <div class="d-flex justify-content-center mt-4">
           {{ $announcements->links() }}
       </div>
   </div>
</div>
@endsection