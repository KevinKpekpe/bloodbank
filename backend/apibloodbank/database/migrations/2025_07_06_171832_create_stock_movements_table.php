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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->foreignId('blood_type_id')->constrained('blood_types')->onDelete('cascade');

            $table->integer('quantity_ml'); // Quantité en millilitres
            $table->enum('movement_type', ['in', 'out', 'adjustment']); // Entrée, sortie, ajustement
            $table->string('reason'); // Raison du mouvement
            $table->foreignId('reference_id')->nullable(); // ID de référence (donation_id ou patient_id)
            $table->string('reference_type')->nullable(); // Type de référence (App\Models\Donation, App\Models\Patient)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
