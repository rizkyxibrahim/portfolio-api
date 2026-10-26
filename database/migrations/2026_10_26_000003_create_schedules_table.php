<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_all_day')->default(false);
            $table->boolean('recurring')->default(false);
            $table->string('recurring_pattern')->nullable(); // daily, weekdays, weekly, monthly
            $table->string('timezone')->default('Asia/Jakarta');
            $table->boolean('is_overnight')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('start_time');
            $table->index('recurring');
            $table->index('timezone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};