<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('diving_experience', ['none', 'beginner', 'intermediate', 'advanced', 'expert'])->nullable();
            $table->string('diving_certification')->nullable();
            $table->text('medical_conditions')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'emergency_contact',
                'emergency_phone',
                'birth_date',
                'diving_experience',
                'diving_certification',
                'medical_conditions',
            ]);
        });
    }
};