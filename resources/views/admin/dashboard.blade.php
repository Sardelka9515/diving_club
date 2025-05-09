@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>系統儀表板</h1>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="adminMenu" data-bs-toggle="dropdown" aria-expanded="false">
                系統管理功能
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminMenu">
                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">用戶管理</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">角色管理</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}">權限管理</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}">系統設定</a></li>
            </ul>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">用戶數量</h6>
                            <h2>{{ $stats['users'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">活動數量</h6>
                            <h2>{{ $stats['activities'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-event fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">公告數量</h6>
                            <h2>{{ $stats['announcements'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-megaphone fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">報名次數</h6>
                            <h2>{{ $stats['registrations'] ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-person-check fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">管理功能</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people fs-1 text-primary mb-2"></i>
                                        <h5>用戶管理</h5>
                                        <p class="text-muted small">管理所有用戶帳號</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.roles.index') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-person-badge fs-1 text-success mb-2"></i>
                                        <h5>角色管理</h5>
                                        <p class="text-muted small">管理系統角色</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.permissions.index') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-key fs-1 text-info mb-2"></i>
                                        <h5>權限管理</h5>
                                        <p class="text-muted small">管理系統權限</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.settings.index') }}" class="text-decoration-none">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body text-center">
                                        <i class="bi bi-gear fs-1 text-warning mb-2"></i>
                                        <h5>系統設定</h5>
                                        <p class="text-muted small">管理網站設定</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">網站概覽</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('admin.activities.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            活動管理
                            <span class="badge bg-primary rounded-pill">{{ $stats['activities'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            公告管理
                            <span class="badge bg-primary rounded-pill">{{ $stats['announcements'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            用戶管理
                            <span class="badge bg-primary rounded-pill">{{ $stats['users'] ?? 0 }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection