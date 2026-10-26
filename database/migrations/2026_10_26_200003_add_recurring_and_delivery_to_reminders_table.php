<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->boolean('recurring')->default(false)->after('is_completed');
            $table->string('recurring_pattern')->nullable()->after('recurring'); // daily, weekdays, weekly, before_event
            $table->string('delivery_status')->default('pending')->after('is_sent'); // pending, sent, failed, delivered
            $table->unsignedInteger('retry_count')->default(0)->after('delivery_status');
            $table->unsignedInteger('max_retries')->default(3)->after('retry_count');
            $table->string('dedupe_key')->unique()->nullable()->after('max_retries');
            $table->index('delivery_status');
            $table->index('dedupe_key');
        });
    }

    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['recurring', 'recurring_pattern', 'delivery_status', 'retry_count', 'max_retries', 'dedupe_key']);
        });
    }
};