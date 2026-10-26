<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('reminder_at');
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->boolean('recurring')->default(false);
            $table->string('recurring_pattern')->nullable(); // daily, weekdays, weekly, before_event
            $table->string('delivery_status')->default('pending'); // pending, sent, failed, delivered
            $table->unsignedInteger('retry_count')->default(0);
            $table->unsignedInteger('max_retries')->default(3);
            $table->string('dedupe_key')->unique()->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('reminder_at');
            $table->index('delivery_status');
            $table->index('dedupe_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};