<!DOCTYPE html>
<html lang="zh-TW">
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
    
    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .navbar {
            background-color: #212529 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }
        
        .navbar-dark .navbar-nav .nav-link:hover,
        .navbar-dark .navbar-nav .nav-link:focus {
            color: #fff;
        }
        
        .navbar-dark .navbar-nav .active > .nav-link,
        .navbar-dark .navbar-nav .nav-link.active {
            color: #fff;
            border-bottom: 2px solid #3490dc;
        }
        
        .wave-icon {
            display: inline-block;
            margin-right: 10px;
        }
        
        main {
            flex: 1;
        }
        
        .footer {
            background-color: #212529;
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        .btn-primary {
            background-color: #3490dc;
            border-color: #3490dc;
        }
        
        .btn-primary:hover {
            background-color: #2779bd;
            border-color: #2779bd;
        }
    </style>
    @stack('styles')
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
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">登入</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">註冊</a>
                        </li>
                    @else
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super'))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i> 系統管理
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">儀表板</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.activities.index') }}">活動管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.announcements.index') }}">公告管理</a></li>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">個人資料</a></li>
                                @if(auth()->user()->hasRole('member'))
                                <li><a class="dropdown-item" href="{{ route('member.activities') }}">我的活動</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">登出</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
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
    @stack('scripts')
</body>
</html>