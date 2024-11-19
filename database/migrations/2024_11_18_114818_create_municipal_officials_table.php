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
        Schema::create('municipal_officials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the official
            $table->string('title'); // Title (e.g., Mayor, Vice Mayor, etc.)
            $table->string('image')->nullable(); // Path to the image (optional)
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
