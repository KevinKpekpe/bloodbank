<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Exécuter les seeders dans l'ordre
        $this->call([
            RoleSeeder::class,
            BloodTypeSeeder::class,
            AdminSeeder::class,
            BloodBankSeeder::class,
            KinshasaBloodBankSeeder::class,
        ]);

        // Créer un utilisateur de test si nécessaire
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
