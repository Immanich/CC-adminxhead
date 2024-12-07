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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name');
            $table->text('description');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->enum('classification', ['SIMPLE', 'COMPLEX', 'SIMPLE - COMPLEX', 'HIGHLY TECHNICAL']);
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // Status column
            $table->json('checklist_of_requirements')->nullable();
            $table->json('where_to_secure')->nullable();
            // $table->string('where_to_secure')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            // $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
