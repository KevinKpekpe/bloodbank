<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrateur du système avec tous les droits'
            ],
            [
                'name' => 'donor',
                'description' => 'Donneur de sang avec accès à son historique et aux banques'
            ],
            [
                'name' => 'blood_bank',
                'description' => 'Gestionnaire de banque de sang avec accès à la gestion du stock'
            ],
            [
                'name' => 'doctor',
                'description' => 'Médecin avec accès aux demandes de sang et aux disponibilités'
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}
