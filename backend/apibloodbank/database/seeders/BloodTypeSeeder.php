<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodTypes = [
            [
                'name' => 'A+',
                'description' => 'Groupe sanguin A positif'
            ],
            [
                'name' => 'A-',
                'description' => 'Groupe sanguin A négatif'
            ],
            [
                'name' => 'B+',
                'description' => 'Groupe sanguin B positif'
            ],
            [
                'name' => 'B-',
                'description' => 'Groupe sanguin B négatif'
            ],
            [
                'name' => 'AB+',
                'description' => 'Groupe sanguin AB positif'
            ],
            [
                'name' => 'AB-',
                'description' => 'Groupe sanguin AB négatif'
            ],
            [
                'name' => 'O+',
                'description' => 'Groupe sanguin O positif'
            ],
            [
                'name' => 'O-',
                'description' => 'Groupe sanguin O négatif'
            ]
        ];

        foreach ($bloodTypes as $bloodType) {
            BloodType::updateOrCreate(
                ['name' => $bloodType['name']],
                $bloodType
            );
        }
    }
}
