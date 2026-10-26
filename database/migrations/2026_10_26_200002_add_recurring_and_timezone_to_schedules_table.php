<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->boolean('recurring')->default(false)->after('is_all_day');
            $table->string('recurring_pattern')->nullable()->after('recurring'); // daily, weekdays, weekly, monthly
            $table->string('timezone')->default('Asia/Jakarta')->after('recurring_pattern');
            $table->boolean('is_overnight')->default(false)->after('timezone');
            $table->index('recurring');
            $table->index('timezone');
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['recurring', 'recurring_pattern', 'timezone', 'is_overnight']);
        });
    }
};