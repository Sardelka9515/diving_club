<!-- resources/views/search.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex align-items-center gap-2 mb-4">
    <form action="{{ route('search') }}" method="GET" class="d-flex flex-grow-1 gap-2">
      <input type="text" name="q" class="form-control w-50" placeholder="輸入關鍵字" value="{{ request('q') }}">
      <select name="sort" class="form-select w-auto">
        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>相關性</option>
        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>最新</option>
      </select>
      <button type="submit" class="btn btn-primary">搜尋</button>
    </form>
  </div>

  <div class="mb-4">
    <h4>活動結果</h4>
    @forelse($activities as $item)
      <a href="{{ route('activities.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark">
        <div class="card-body">
          <h5>{{ $item->title }}</h5>
          <p>{{ $item->description }}</p>
        </div>
      </a>
    @empty
      <p>沒有找到相關活動。</p>
    @endforelse
  </div>

  <div class="mb-4">
    <h4>公告結果</h4>
    @forelse($announcements as $item)
      <a href="{{ route('announcements.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark">
        <div class="card-body">
          <h5>{{ $item->title }}</h5>
          <p>{{ $item->content }}</p>
        </div>
      </a>
    @empty
      <p>沒有找到相關公告。</p>
    @endforelse
  </div>

  @if($recentKeywords->isNotEmpty())
    <div class="mt-4">
      <label class="form-label fw-bold">最近搜尋：</label>
      <div class="d-flex flex-wrap gap-2 mb-1">
        @foreach($recentKeywords as $keyword)
          <a href="{{ route('search', ['q' => $keyword]) }}" class="badge bg-secondary text-decoration-none">{{ $keyword }}</a>
        @endforeach
      </div>
      <form action="{{ route('search.clearLogs') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-danger" style="font-size: 0.85rem;">清除紀錄</button>
      </form>
    </div>
  @endif
</div>
@endsection
