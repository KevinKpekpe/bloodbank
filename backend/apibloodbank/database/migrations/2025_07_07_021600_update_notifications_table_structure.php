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
        Schema::table('notifications', function (Blueprint $table) {
            // Supprimer l'ancienne colonne is_read
            $table->dropColumn('is_read');

            // Modifier le type enum pour correspondre à nos nouveaux types
            $table->enum('type', ['appointment', 'reminder', 'donation_completed', 'system'])->change();

            // Ajouter la nouvelle colonne read_at
            $table->timestamp('read_at')->nullable()->after('data');

            // Ajouter des index pour améliorer les performances
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Supprimer les index
            $table->dropIndex(['user_id', 'read_at']);
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['user_id', 'created_at']);

            // Supprimer read_at
            $table->dropColumn('read_at');

            // Restaurer l'ancien type enum
            $table->enum('type', ['info', 'warning', 'error', 'success'])->change();

            // Restaurer is_read
            $table->boolean('is_read')->default(false);
        });
    }
};
