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
        Schema::create('services_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->integer('step')->nullable();
            $table->string('info_title')->nullable();
            $table->json('clients')->nullable();
            $table->json('agency_action')->nullable();
            $table->json('fees')->nullable();
            $table->json('processing_time')->nullable();
            $table->json('person_responsible')->nullable();
            $table->string('total_fees')->nullable();
            $table->string('total_response_time')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services_infos');
    }
};
