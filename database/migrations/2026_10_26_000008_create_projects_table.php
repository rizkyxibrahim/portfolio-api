<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false)->after('is_featured');
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_featured');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
