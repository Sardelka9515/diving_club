<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', '潛水社') }} - @yield('title', '探索海洋世界')</title>

    <!-- 字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- 增加夢幻字體 -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 圖標 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- 使用 Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- 備用 CDN 引入 Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    {{-- <link href="{{ asset('css/diving-club.css') }}" rel="stylesheet"> --}}

    @stack('styles')
    
    <script>
        window.recentKeywords = @json($recentKeywords->pluck('keyword')->unique()->take(5));
        window.reserveKeywords = @json($reserveKeywords);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/search.js'])


    <!-- Inline Critical CSS -->
    <style>
        :root {
            --ocean-deep: #05445E;
            --ocean-medium: #189AB4;
            --ocean-light: #75E6DA;
            --ocean-foam: #D4F1F9;
        }
        
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background-color: #f8f9fa;
            background-image: linear-gradient(180deg, rgba(212, 241, 249, 0.3) 0%, rgba(255, 255, 255, 1) 100%);
            color: #333;
        }
        
        h1, h2, h3, h4, h5, .navbar-brand {
            font-family: 'Quicksand', 'Noto Sans TC', sans-serif;
            font-weight: 600;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--ocean-deep) 0%, var(--ocean-medium) 100%);
            box-shadow: 0 2px 15px rgba(5, 68, 94, 0.2);
            padding: 0.8rem 1rem;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .wave-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .wave-icon i {
            font-size: 20px;
            z-index: 1;
            color: white;
        }
        
        .wave-icon::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 10px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: wave 2s infinite ease-in-out;
        }
        
        @keyframes wave {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: var(--ocean-light);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after, .nav-link.active::after {
            width: 80%;
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background-color: white;
            overflow: hidden;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--ocean-foam);
            transform: translateX(5px);
        }
        
        /* Glassmorphism Card styling */
        .card {
            border-radius: 1rem;
            border: none;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.1);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(31, 38, 135, 0.15);
        }
        
        /* Buttons with ocean gradient */
        .btn-ocean {
            background: linear-gradient(135deg, var(--ocean-medium) 0%, var(--ocean-deep) 100%);
            border: none;
            color: white;
            position: relative;
            overflow: hidden;
            z-index: 1;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-ocean::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .btn-ocean:hover::before {
            width: 100%;
        }
        
        /* Footer styling */
        .footer {
            background: var(--ocean-deep);
            border-top: 5px solid var(--ocean-medium);
            position: relative;
            overflow: hidden;
        }
        
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, transparent, var(--ocean-light), transparent);
            animation: wave-footer 3s infinite linear;
        }
        
        @keyframes wave-footer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Alert customization */
        .alert {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .alert-success {
            background-color: #d4edda;
            border-left: 5px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-left: 5px solid #dc3545;
        }
    </style>
</head>

<body>
    <!-- 頂部導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <span class="wave-icon">
                    <i class="bi bi-water"></i>
                </span>
                潛水社
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="bi bi-house-door"></i> 首頁
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('activities*') ? 'active' : '' }}"
                            href="{{ route('activities.index') }}">
                            <i class="bi bi-calendar-event"></i> 活動
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}"
                            href="{{ route('announcements.index') }}">
                            <i class="bi bi-megaphone"></i> 公告
                        </a>
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
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                                href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> 登入
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
                                href="{{ route('register') }}">
                                <i class="bi bi-person-plus"></i> 註冊
                            </a>
                        </li>
                    @else
                        <!-- 管理員選項 - 只有 admin 和 super 可見 -->
                        @if (auth()->user()->hasRole(['admin', 'super']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i> 系統管理
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>儀表板</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.activities.index') }}"><i class="bi bi-calendar-check me-2"></i>活動管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-dots me-2"></i>評論管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.reports.index') }}"><i class="bi bi-flag me-2"></i>舉報管理</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.announcements.index') }}"><i class="bi bi-megaphone me-2"></i>公告管理</a>
                                    </li>

                                    <!-- 超級管理員專用功能 -->
                                    @if (auth()->user()->hasRole('super'))
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>用戶管理</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}"><i class="bi bi-person-badge me-2"></i>角色管理</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}"><i class="bi bi-shield-lock me-2"></i>權限管理</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="bi bi-sliders me-2"></i>系統設定</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        <!-- 用戶下拉選單 -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-columns-gap me-2"></i>我的儀表板</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>個人資料</a></li>
                                <li><a class="dropdown-item" href="{{ route('member.activities') }}"><i class="bi bi-calendar me-2"></i>我的活動</a></li>
                                <li><a class="dropdown-item" href="{{ route('member.comments') }}"><i class="bi bi-chat-left-text me-2"></i>我的評論</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i> 登出
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

    <main class="py-5">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" data-aos="fade-down" data-aos-duration="800">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" data-aos="fade-down" data-aos-duration="800">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="mb-3">潛水社</h5>
                    <p class="mb-3">探索海洋世界的最佳夥伴</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="mb-3">快速連結</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/') }}" class="text-white text-decoration-none mb-2 d-inline-block">首頁</a></li>
                        <li><a href="{{ route('activities.index') }}" class="text-white text-decoration-none mb-2 d-inline-block">活動</a></li>
                        <li><a href="{{ route('announcements.index') }}" class="text-white text-decoration-none mb-2 d-inline-block">公告</a></li>
                    </ul>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="mb-3">聯絡我們</h5>
                    <p><i class="bi bi-geo-alt-fill me-2"></i>台灣某某縣市某某區</p>
                    <p><i class="bi bi-envelope-fill me-2"></i>info@divingclub.com</p>
                    <p><i class="bi bi-telephone-fill me-2"></i>+886 123 456 789</p>
                </div>
            </div>
            <div class="row mt-4 pt-3 border-top border-light">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} 潛水社. 保留所有權利。</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true
            });
            
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

            // Add ripple effect to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const x = e.clientX - e.target.getBoundingClientRect().left;
                    const y = e.clientY - e.target.getBoundingClientRect().top;
                    
                    const ripple = document.createElement('span');
                    ripple.classList.add('ripple-effect');
                    ripple.style.left = `${x}px`;
                    ripple.style.top = `${y}px`;
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
    </script>
    @stack('modals')
    @stack('scripts')
</body>

</html>