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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            // $table->string('name')->nullable();
            $table->string('name')->nullable();
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->text('feedback')->nullable();
            $table->text('reply')->nullable();
            // $table->enum('status', ['active', 'archived'])->default('active');
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
