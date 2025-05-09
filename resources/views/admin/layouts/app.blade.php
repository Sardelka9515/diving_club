<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>管理後台 - {{ config('app.name', '潛水社') }}</title>
    
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
        
        .wave-icon {
            display: inline-block;
            margin-right: 10px;
        }
        
        .admin-sidebar {
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
            border-right: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        .admin-sidebar .nav-link {
            color: #495057;
            border-radius: 0;
            padding: 0.5rem 1rem;
        }
        
        .admin-sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        
        .admin-sidebar .nav-link.active {
            color: #007bff;
            background-color: #e9ecef;
            font-weight: 500;
        }
        
        .admin-sidebar .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        
        .admin-content {
            padding: 20px;
            flex: 1;
        }
        
        .footer {
            background-color: #212529;
            color: white;
            padding: 1rem 0;
            margin-top: auto;
        }
        
        @media (max-width: 767.98px) {
            .admin-sidebar {
                min-height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                margin-bottom: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- 頂部導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
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
                        <a class="nav-link" href="{{ url('/') }}">返回前台</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">個人資料</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">登出</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- 側邊欄 -->
            <div class="col-md-3 col-lg-2 admin-sidebar d-md-block collapse">
                <div class="pt-3">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> 儀表板
                        </a>
                        <a href="{{ route('admin.activities.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i> 活動管理
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                            <i class="bi bi-megaphone"></i> 公告管理
                        </a>
                    </div>
                    
                    @if(auth()->user()->hasRole('super'))
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
                </div>
            </div>
            
            <!-- 主要內容 -->
            <main class="col-md-9 col-lg-10 admin-content">
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
            </main>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} 潛水社. 保留所有權利。</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <!-- Bootstrap JS 和下拉選單初始化 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 手動初始化所有下拉選單
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.forEach(function(element) {
                new bootstrap.Dropdown(element);
            });
        });
    </script>
    @stack('scripts')
    </body>
</body>
</html>