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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->foreignId('blood_type_id')->constrained('blood_types')->onDelete('cascade');

            // Informations médicales
            $table->string('doctor_name');
            $table->string('hospital_name');
            $table->string('phone');
            $table->string('email');
            $table->enum('urgency_level', ['normal', 'urgent', 'critical'])->default('normal');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
