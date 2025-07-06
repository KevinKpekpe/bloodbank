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
        Schema::table('users', function (Blueprint $table) {
            // Champs pour les donneurs
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignId('blood_type_id')->nullable()->constrained('blood_types')->onDelete('set null');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->boolean('is_eligible_donor')->default(true);

            // Rôle utilisateur
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['blood_type_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn([
                'phone', 'address', 'city', 'postal_code', 'blood_type_id',
                'date_of_birth', 'gender', 'is_eligible_donor', 'role_id'
            ]);
        });
    }
};
