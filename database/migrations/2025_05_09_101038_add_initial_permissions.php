<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddInitialPermissions extends Migration
{
    public function up()
    {
        // 插入初始權限
        $permissions = [
            ['name' => '管理活動', 'slug' => 'manage-activities', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理公告', 'slug' => 'manage-announcements', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理社員福利', 'slug' => 'manage-benefits', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理用戶', 'slug' => 'manage-users', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理角色', 'slug' => 'manage-roles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理權限', 'slug' => 'manage-permissions', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '管理系統設定', 'slug' => 'manage-settings', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '提前報名', 'slug' => 'early-registration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '查看社員福利', 'slug' => 'view-benefits', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '使用聊天室', 'slug' => 'use-chat', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('permissions')->insert($permissions);
        
        // 給角色分配權限
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();
        $memberRole = DB::table('roles')->where('slug', 'member')->first();
        $superRole = DB::table('roles')->where('slug', 'super')->first();
        
        if ($adminRole) {
            $adminPermissions = DB::table('permissions')
                ->whereIn('slug', ['manage-activities', 'manage-announcements', 'manage-benefits'])
                ->pluck('id');
                
            foreach ($adminPermissions as $permissionId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRole->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        if ($memberRole) {
            $memberPermissions = DB::table('permissions')
                ->whereIn('slug', ['early-registration', 'view-benefits', 'use-chat'])
                ->pluck('id');
                
            foreach ($memberPermissions as $permissionId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $memberRole->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        if ($superRole) {
            $allPermissions = DB::table('permissions')->pluck('id');
                
            foreach ($allPermissions as $permissionId) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $superRole->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();
    }
}