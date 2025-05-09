<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // 插入初始設定
        DB::table('settings')->insert([
            ['key' => 'site_name', 'value' => '潛水社', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => '探索海洋世界的最佳夥伴', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'contact@divingclub.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_text', 'value' => '© 2025 潛水社. 保留所有權利。', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_mode', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}