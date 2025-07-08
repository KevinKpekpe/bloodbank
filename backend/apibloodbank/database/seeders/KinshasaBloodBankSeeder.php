<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodBank;
use App\Models\User;
use App\Models\Role;
use App\Models\BloodType;
use App\Models\BloodStock;
use Illuminate\Support\Facades\Hash;

class KinshasaBloodBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le rôle admin
        $adminRole = Role::where('name', 'admin')->first();
        $bloodBankRole = Role::where('name', 'blood_bank')->first();

        if (!$adminRole || !$bloodBankRole) {
            $this->command->error('Les rôles admin et blood_bank doivent exister. Exécutez d\'abord RoleSeeder.');
            return;
        }

        // Créer l'administrateur de la banque de sang de Kinshasa
        $kinshasaAdmin = User::updateOrCreate(
            ['email' => 'admin@kinshasa-bloodbank.cd'],
            [
                'name' => 'Dr. Jean-Pierre Mwamba',
                'email' => 'admin@kinshasa-bloodbank.cd',
                'password' => Hash::make('password123'),
                'phone' => '+243 81 234 5678',
                'address' => 'Avenue du Commerce, 123',
                'city' => 'Kinshasa',
                'postal_code' => '00100',
                'country' => 'République Démocratique du Congo',
                'latitude' => -4.4419,
                'longitude' => 15.2663,
                'blood_type_id' => 7, // O+
                'role_id' => $bloodBankRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Créer la banque de sang de Kinshasa
        $kinshasaBank = BloodBank::updateOrCreate(
            ['email' => 'contact@kinshasa-bloodbank.cd'],
            [
                'name' => 'Centre National de Transfusion Sanguine de Kinshasa',
                'description' => 'Centre principal de transfusion sanguine de la République Démocratique du Congo, situé à Kinshasa. Nous fournissons des services de collecte, traitement et distribution de sang pour tous les hôpitaux de la région.',
                'phone' => '+243 81 234 5678',
                'email' => 'contact@kinshasa-bloodbank.cd',
                'website' => 'https://www.kinshasa-bloodbank.cd',
                'address' => 'Avenue du Commerce, 123',
                'city' => 'Kinshasa',
                'postal_code' => '00100',
                'country' => 'République Démocratique du Congo',
                'latitude' => -4.4419,
                'longitude' => 15.2663,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'public',
                'admin_id' => $kinshasaAdmin->id,
            ]
        );

        // Associer l'admin à la banque
        $kinshasaAdmin->update(['blood_bank_id' => $kinshasaBank->id]);

        // Récupérer tous les types de sang
        $bloodTypes = BloodType::all();

        // Créer les stocks pour chaque type de sang
        foreach ($bloodTypes as $bloodType) {
            // Quantités réalistes pour une banque de sang africaine
            $quantities = [
                'A+' => 8500,    // Très demandé
                'A-' => 1200,    // Moins demandé
                'B+' => 6500,    // Assez demandé
                'B-' => 800,     // Moins demandé
                'AB+' => 3000,   // Modérément demandé
                'AB-' => 400,    // Rare
                'O+' => 12000,   // Le plus demandé (donneur universel)
                'O-' => 1500,    // Rare mais très précieux
            ];

            $quantity = $quantities[$bloodType->name] ?? 2000;
            $minimumThreshold = $quantity * 0.2; // 20% du stock comme seuil minimum
            $maximumCapacity = $quantity * 2; // Capacité double du stock actuel

            BloodStock::updateOrCreate(
                [
                    'blood_bank_id' => $kinshasaBank->id,
                    'blood_type_id' => $bloodType->id,
                ],
                [
                    'quantity_ml' => $quantity,
                    'minimum_threshold' => $minimumThreshold,
                    'maximum_capacity' => $maximumCapacity,
                    'last_updated' => now(),
                    'last_updated_by' => $kinshasaAdmin->id,
                ]
            );
        }

        // Créer quelques stocks avec des alertes (stocks faibles)
        $lowStockTypes = ['A-', 'B-', 'AB-', 'O-'];
        foreach ($lowStockTypes as $typeName) {
            $bloodType = BloodType::where('name', $typeName)->first();
            if ($bloodType) {
                $stock = BloodStock::where('blood_bank_id', $kinshasaBank->id)
                    ->where('blood_type_id', $bloodType->id)
                    ->first();

                if ($stock) {
                    // Mettre le stock en alerte (quantité inférieure au seuil)
                    $stock->update([
                        'quantity_ml' => $stock->minimum_threshold - 200,
                        'last_updated' => now(),
                        'last_updated_by' => $kinshasaAdmin->id,
                    ]);
                }
            }
        }

        $this->command->info('Banque de sang de Kinshasa créée avec succès !');
        $this->command->info('Admin: admin@kinshasa-bloodbank.cd / password123');
        $this->command->info('Banque: Centre National de Transfusion Sanguine de Kinshasa');
    }
}
