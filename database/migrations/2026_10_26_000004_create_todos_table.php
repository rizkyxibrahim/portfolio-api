<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->dateTime('scheduled_start_at')->nullable();
            $table->dateTime('scheduled_end_at')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->string('priority')->default('medium'); // low, medium, high
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_completed');
            $table->index('scheduled_start_at');
            $table->index('due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};