<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        // 如果沒有登入的用戶
        if (!$request->user()) {
            return redirect('/login')->with('error', '請先登入');
        }
        
        // 將角色字串拆分為數組
        $rolesArray = explode(',', $roles);
        
        // 檢查用戶是否擁有任一所需角色
        foreach ($rolesArray as $role) {
            if ($request->user()->hasRole(trim($role))) {
                return $next($request);
            }
        }
        
        // 用戶沒有任何所需角色
        return redirect('/')->with('error', '您沒有權限訪問此頁面');
    }
}