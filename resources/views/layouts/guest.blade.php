<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '潛水社') }}</title>
    
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
            background-image: url('https://source.unsplash.com/random/1920x1080/?underwater,diving');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        
        .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        .active > .nav-link {
            color: white !important;
            border-bottom: 2px solid #0077b6;
        }
        
        .container {
            position: relative;
            z-index: 1;
        }
        
        .card {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95);
        }
        
        .bg-primary {
            background-color: #0077b6 !important;
        }
        
        .btn-primary {
            background-color: #0077b6;
            border-color: #0077b6;
        }
        
        .btn-primary:hover {
            background-color: #023e8a;
            border-color: #023e8a;
        }
        
        .auth-container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
    </style>
</head>
<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-water me-2"></i>
                {{ config('app.name', '潛水社') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">首頁</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('activities.index') }}">活動</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('announcements.index') }}">公告</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" href="{{ route('login') }}">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('register') ? 'active' : '' }}" href="{{ route('register') }}">註冊</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center text-white mb-4">
                <h1 class="display-4 fw-bold">潛水社</h1>
                <p class="lead">探索海洋世界的最佳夥伴</p>
            </div>
        </div>
        
        {{ $slot }}
    </div>
    
    <!-- 頁腳 -->
    <footer class="text-center text-white py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} 潛水社. 保留所有權利。</p>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>