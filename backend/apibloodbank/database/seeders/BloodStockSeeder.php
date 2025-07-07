<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodBank;
use App\Models\BloodType;
use App\Models\BloodStock;

class BloodStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodBanks = BloodBank::all();
        $bloodTypes = BloodType::all();

        foreach ($bloodBanks as $bank) {
            foreach ($bloodTypes as $bloodType) {
                // Créer un stock aléatoire pour chaque type de sang
                $quantity = rand(500, 5000); // Entre 500ml et 5000ml
                $minimumThreshold = 1000; // Seuil minimum de 1000ml

                BloodStock::create([
                    'blood_bank_id' => $bank->id,
                    'blood_type_id' => $bloodType->id,
                    'quantity_ml' => $quantity,
                    'minimum_threshold' => $minimumThreshold,
                    'last_updated' => now()
                ]);
            }
        }

        $this->command->info('Blood stocks seeded successfully!');
    }
}
