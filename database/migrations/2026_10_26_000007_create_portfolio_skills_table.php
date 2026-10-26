<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced, expert
            $table->integer('years_of_experience')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_skills');
    }
};