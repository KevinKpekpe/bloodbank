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
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requesting_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->foreignId('responding_bank_id')->constrained('blood_banks')->onDelete('cascade');
            $table->enum('partnership_type', ['sharing', 'referral', 'training', 'research']);
            $table->text('description');
            $table->text('terms')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'terminated'])->default('pending');
            $table->text('response_notes')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamps();

            // Index pour éviter les doublons
            $table->unique(['requesting_bank_id', 'responding_bank_id', 'partnership_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnerships');
    }
};
