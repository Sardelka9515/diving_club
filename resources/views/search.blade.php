<!-- resources/views/search.blade.php -->
@extends('layouts.app')

@section('content')
  <div class="container">
  <div class="d-flex justify-content-end mb-2">
    <form action="{{ route('search') }}" method="GET" class="d-flex align-items-center">
      <input type="hidden" name="q" value="{{ request('q') }}">
      <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>相關性</option>
        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>時間（晚到早）</option>
        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>時間（早到晚）</option>
      </select>
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

  <div class="mb-4 mt-5">
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

  
</div>
@endsection
