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
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types')->onDelete('cascade');

            $table->integer('quantity_ml')->default(0); // Stock actuel en millilitres
            $table->integer('minimum_threshold')->default(1000); // Seuil d'alerte (1L)
            $table->integer('maximum_capacity')->default(10000); // Capacité maximale (10L)

            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            // Index unique pour éviter les doublons
            $table->unique(['blood_bank_id', 'blood_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_stocks');
    }
};
