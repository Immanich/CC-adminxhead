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
        Schema::create('elected_officials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('image')->nullable();
            $table->year('start_year')->default('2023');
            $table->year('end_year')->default('2025');
            $table->enum('status', ['current', 'expired', 'upcoming'])->default('current');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipal_officials');
    }
};
