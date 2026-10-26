<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dateTime('scheduled_start_at')->nullable()->after('description');
            $table->dateTime('scheduled_end_at')->nullable()->after('scheduled_start_at');
            $table->dateTime('due_at')->nullable()->after('scheduled_end_at');
            $table->index('scheduled_start_at');
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['scheduled_start_at', 'scheduled_end_at', 'due_at']);
        });
    }
};