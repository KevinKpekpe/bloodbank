<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BloodBank;
use App\Models\User;
use App\Models\Role;

class BloodBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::where('role_id', $adminRole->id)->first();

        $bloodBanks = [
            [
                'name' => 'Centre de Transfusion Sanguine de Paris',
                'description' => 'Centre principal de transfusion sanguine de la région parisienne',
                'phone' => '01 44 49 30 00',
                'email' => 'contact@cts-paris.fr',
                'website' => 'https://www.cts-paris.fr',
                'address' => '6 rue Alexandre Cabanel',
                'city' => 'Paris',
                'postal_code' => '75015',
                'country' => 'France',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'public',
                'admin_id' => $admin->id,
            ],
            [
                'name' => 'Établissement Français du Sang - Lyon',
                'description' => 'Centre de collecte et de distribution de sang à Lyon',
                'phone' => '04 72 68 70 00',
                'email' => 'contact@efs-lyon.fr',
                'website' => 'https://www.efs.sante.fr',
                'address' => '1 rue du Professeur Calmette',
                'city' => 'Lyon',
                'postal_code' => '69003',
                'country' => 'France',
                'latitude' => 45.7578,
                'longitude' => 4.8320,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'public',
                'admin_id' => $admin->id,
            ],
            [
                'name' => 'Centre Hospitalier Universitaire de Marseille',
                'description' => 'Banque de sang du CHU de Marseille',
                'phone' => '04 91 38 00 00',
                'email' => 'banque.sang@chu-marseille.fr',
                'website' => 'https://www.chu-marseille.fr',
                'address' => '264 rue Saint-Pierre',
                'city' => 'Marseille',
                'postal_code' => '13005',
                'country' => 'France',
                'latitude' => 43.2965,
                'longitude' => 5.3698,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'public',
                'admin_id' => $admin->id,
            ],
            [
                'name' => 'Clinique Privée du Sang - Toulouse',
                'description' => 'Clinique privée spécialisée dans la transfusion sanguine',
                'phone' => '05 61 32 25 00',
                'email' => 'info@clinique-sang-toulouse.fr',
                'website' => 'https://www.clinique-sang-toulouse.fr',
                'address' => '15 avenue des Minimes',
                'city' => 'Toulouse',
                'postal_code' => '31200',
                'country' => 'France',
                'latitude' => 43.6047,
                'longitude' => 1.4442,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'private',
                'admin_id' => $admin->id,
            ],
            [
                'name' => 'Association Donneurs de Sang Bénévoles - Nantes',
                'description' => 'Association de donneurs bénévoles de la région nantaise',
                'phone' => '02 40 20 50 00',
                'email' => 'contact@donneurs-nantes.fr',
                'website' => 'https://www.donneurs-nantes.fr',
                'address' => '8 quai Moncousu',
                'city' => 'Nantes',
                'postal_code' => '44000',
                'country' => 'France',
                'latitude' => 47.2184,
                'longitude' => -1.5536,
                'is_verified' => true,
                'is_active' => true,
                'partnership_level' => 'associative',
                'admin_id' => $admin->id,
            ]
        ];

        foreach ($bloodBanks as $bloodBank) {
            BloodBank::updateOrCreate(
                ['email' => $bloodBank['email']],
                $bloodBank
            );
        }
    }
}
