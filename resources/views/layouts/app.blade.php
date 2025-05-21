<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '潛水社') }} - @yield('title', '探索海洋世界')</title>
    
    <!-- 字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 圖標 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- 使用 Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- 備用 CDN 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @stack('styles')

    <script>
        window.recentKeywords = @json($recentKeywords->pluck('keyword')->unique()->take(5));
        window.reserveKeywords = @json($reserveKeywords);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])

</head>

<body>
    <!-- 頂部導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="wave-icon">
                    <i class="bi bi-water"></i>
                </span>
                潛水社
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">首頁</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('activities*') ? 'active' : '' }}" href="{{ route('activities.index') }}">活動</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">公告</a>
                    </li>
                </ul>

                <!-- 搜尋欄 -->
                <form action="{{ route('search') }}" method="GET" role="search" class="d-flex justify-content-end" style="margin-left: 200px;">
                <div style="position: relative; width: 320px;">
                    <input
                    id="searchInput"
                    name="q"
                    type="search"
                    class="form-control me-2"
                    placeholder="搜尋活動或公告"
                    value="{{ request('q') }}"
                    autocomplete="off"
                    required
                    aria-label="搜尋"
                    style="width: 100%;"
                    />

                    <ul id="searchSuggestions" class="list-group"
                        style="position: absolute; top: 100%; width: 100%; z-index: 1000; display: none; max-height: 280px; overflow-y: auto;">
                    @foreach ($recentKeywords as $keyword)
                        <li class="list-group-item d-flex justify-content-between align-items-center search-suggestion-item"
                            data-keyword="{{ $keyword }}">
                        <span>{{ $keyword }}</span>
                        <button
                            type="button"
                            class="remove-keyword-btn"
                            data-keyword="{{ $keyword }}"
                            style="border: none; background: none; color: #888; font-size: 16px;">
                            ×
                        </button>
                        </li>
                    @endforeach

                    @if ($recentKeywords->isNotEmpty())
                        <li id="clearSearchItem" class="list-group-item">
                            <span class="text-danger ms-1" style="cursor:pointer;" id="clearAllKeywords">清除紀錄</span>

                        </li>
                    @endif
                    </ul>
                </div>

                <button type="submit" class="btn btn-outline-light btn-sm ms-2">搜尋</button>
                </form>

                <!-- 導航部分 -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">登入</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">註冊</a>
                        </li>
                    @else
                        <!-- 管理員選項 - 只有 admin 和 super 可見 -->
                        @if(auth()->user()->hasRole(['admin', 'super']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear-fill"></i> 系統管理
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">儀表板</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.activities.index') }}">活動管理</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.announcements.index') }}">公告管理</a></li>
                                
                                <!-- 超級管理員專用功能 -->
                                @if(auth()->user()->hasRole('super'))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">用戶管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">角色管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}">權限管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">系統設定</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        
                        <!-- 用戶下拉選單 -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">我的儀表板</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">個人資料</a></li>
                                <li><a class="dropdown-item" href="{{ route('member.activities') }}">我的活動</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> 登出
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <footer class="footer bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>潛水社</h5>
                    <p>探索海洋世界的最佳夥伴</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} 潛水社. 保留所有權利。</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 使用 Bootstrap 5 Dropdown API
            var dropdownElementList = document.querySelectorAll('.dropdown-toggle');
            dropdownElementList.forEach(function(element) {
                // 創建一個新的 Dropdown 實例
                var dropdown = new bootstrap.Dropdown(element, {
                    autoClose: true
                });
                
                // 確保點擊後可以開啟下拉選單
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    dropdown.toggle();
                });
            });
            
            console.log('Bootstrap Dropdown API 初始化，總數量: ' + dropdownElementList.length);
        });
    </script>
    @stack('scripts')
</body>
</html>