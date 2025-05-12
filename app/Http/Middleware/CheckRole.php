<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // 如果沒有登入的用戶
        if (!$request->user()) {
            return redirect('/login')->with('error', '請先登入');
        }
        
        // 超級管理員擁有所有權限，直接通過
        if ($request->user()->hasRole('super')) {
            return $next($request);
        }
        
        // 將角色字串拆分為數組
        $rolesArray = explode(',', $roles);
        
        // 檢查用戶是否擁有任一所需角色
        foreach ($rolesArray as $role) {
            if ($request->user()->hasRole(trim($role))) {
                return $next($request);
            }
        }
        
        // 用戶沒有任何所需角色，返回 403 錯誤
        abort(403, '您沒有權限訪問此頁面');
    }
}