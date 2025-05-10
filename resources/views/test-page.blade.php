@extends('layouts.app')

@section('title', '測試頁面')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">身份測試頁面</div>

                <div class="card-body">
                    <h3>用戶狀態</h3>
                    <p>是否登入: {{ $isLoggedIn ? '是' : '否' }}</p>
                    
                    @if($isLoggedIn)
                        <h4>用戶資訊</h4>
                        <p>名稱: {{ $user->name }}</p>
                        <p>郵箱: {{ $user->email }}</p>
                        
                        <h4>用戶角色</h4>
                        @if(count($roles) > 0)
                            <ul>
                                @foreach($roles as $role)
                                    <li>{{ $role }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>沒有分配角色</p>
                        @endif
                        
                        <h4>測試連結</h4>
                        <div class="list-group">
                            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">儀表板</a>
                            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">個人資料</a>
                            <a href="{{ route('member.activities') }}" class="list-group-item list-group-item-action">我的活動</a>
                            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">管理後台</a>
                        </div>
                        
                        <h4>登出測試</h4>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger">登出</button>
                        </form>
                    @else
                        <p>請先登入</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">登入</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection