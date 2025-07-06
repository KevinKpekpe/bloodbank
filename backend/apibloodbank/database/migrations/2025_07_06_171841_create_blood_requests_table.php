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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types')->onDelete('cascade');

            $table->integer('quantity_ml'); // Quantité demandée en millilitres
            $table->enum('urgency_level', ['normal', 'urgent', 'critical'])->default('normal');
            $table->enum('status', ['pending', 'approved', 'fulfilled', 'cancelled'])->default('pending');

            $table->date('requested_date');
            $table->date('needed_by_date')->nullable(); // Date limite si urgente
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
