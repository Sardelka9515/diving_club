<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_start');
            $table->dateTime('registration_end');
            $table->integer('max_participants')->default(0);
            $table->string('location');
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('activity_category_id')->constrained()->onDelete('cascade');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
