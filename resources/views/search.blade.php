@extends('layouts.app')

@section('content')
  <div class="container">
    <!-- 分頁切換選單與排序選單 -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'activities'])) }}"
           class="fs-5 me-2 {{ request('tab', 'activities') === 'activities' ? 'fw-bold text-primary' : 'text-muted' }}"
           style="text-decoration: none;">
          活動
        </a>
        |
        <a href="{{ route('search', array_merge(request()->query(), ['tab' => 'announcements'])) }}"
           class="fs-5 ms-2 {{ request('tab') === 'announcements' ? 'fw-bold text-primary' : 'text-muted' }}"
           style="text-decoration: none;">
          公告
        </a>
      </div>
      <form action="{{ route('search') }}" method="GET" class="d-flex align-items-center">
        <input type="hidden" name="q" value="{{ request('q') }}">
        <input type="hidden" name="tab" value="{{ request('tab', 'activities') }}">
        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 150px;">
          <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>相關性</option>
          <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>時間（晚到早）</option>
          <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>時間（早到晚）</option>
        </select>
      </form>
    </div>

    @if(request('tab', 'activities') === 'activities')
      <div class="mb-4">
        @if($activities->count())
          @foreach($activities as $item)
            <a href="{{ route('activities.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark" style="min-height: 110px;">
              <div class="card-body d-flex flex-column justify-content-center">
                <h5>{{ $item->title }}</h5>
                <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                  {{ $item->description ?? strip_tags($item->content) }}
                </p>
                
              </div>
            </a>
          @endforeach
        @else
          <p>沒有找到相關活動，顯示全部活動：</p>
          @foreach($fallbackActivities as $item)
            <a href="{{ route('activities.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark" style="min-height: 110px;">
              <div class="card-body d-flex flex-column justify-content-center">
                <h5>{{ $item->title }}</h5>
                <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                  {{ $item->description ?? strip_tags($item->content) }}
                </p>
              </div>
            </a>
          @endforeach
        @endif
        <div class="mt-4">
          {{ $activities->count() ? $activities->appends(request()->except('page'))->links('pagination::bootstrap-5') : $fallbackActivities->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
        </div>
      </div>


    @else
      <div class="mb-4">
        @if($announcements->count())
          @foreach($announcements as $item)
            <a href="{{ route('announcements.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark" style="min-height: 110px;">
              <div class="card-body d-flex flex-column justify-content-center">
                <h5>{{ $item->title }}</h5>
                <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                  {{ $item->description ?? strip_tags($item->content) }}
                </p>
              </div>
            </a>
          @endforeach
        @else
          <p>沒有找到相關公告，顯示全部公告：</p>
          @foreach($fallbackAnnouncements as $item)
            <a href="{{ route('announcements.show', $item->id) }}" class="card mb-2 text-decoration-none text-dark" style="min-height: 110px;">
              <div class="card-body d-flex flex-column justify-content-center">
                <h5>{{ $item->title }}</h5>
                <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                  {{ $item->description ?? strip_tags($item->content) }}
                </p>
              </div>
            </a>
          @endforeach
        @endif
        {{ $announcements->count() ? $announcements->appends(request()->except('page'))->links('pagination::bootstrap-5') : $fallbackAnnouncements->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
      </div>
    @endif
  </div>
@endsection

