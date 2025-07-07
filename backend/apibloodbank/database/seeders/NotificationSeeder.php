<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return;
        }

        foreach ($users as $user) {
            // Notification de confirmation de rendez-vous
            Notification::create([
                'user_id' => $user->id,
                'type' => 'appointment',
                'title' => 'Rendez-vous confirmé',
                'message' => 'Votre rendez-vous de don de sang a été confirmé pour le 15/07/2024 à 14h00 au Centre de transfusion sanguine de Paris. N\'oubliez pas d\'apporter une pièce d\'identité.',
                'data' => [
                    'appointment_id' => 1,
                    'scheduled_at' => '2024-07-15 14:00:00',
                    'blood_bank_name' => 'Centre de transfusion sanguine de Paris',
                    'blood_bank_address' => '123 Rue de la Paix, 75001 Paris'
                ],
                'read_at' => null
            ]);

            // Notification de rappel
            Notification::create([
                'user_id' => $user->id,
                'type' => 'reminder',
                'title' => 'Rappel de rendez-vous',
                'message' => 'N\'oubliez pas votre rendez-vous de don de sang demain à 14h00 au Centre de transfusion sanguine de Paris.',
                'data' => [
                    'appointment_id' => 1,
                    'scheduled_at' => '2024-07-15 14:00:00',
                    'blood_bank_name' => 'Centre de transfusion sanguine de Paris'
                ],
                'read_at' => null
            ]);

            // Notification de don complété
            Notification::create([
                'user_id' => $user->id,
                'type' => 'donation_completed',
                'title' => 'Don de sang complété',
                'message' => 'Votre don de sang a été complété avec succès. Merci pour votre générosité ! Vous pouvez faire un nouveau don dans 56 jours.',
                'data' => [
                    'donation_id' => 1,
                    'donation_date' => '2024-06-01',
                    'next_donation_date' => '2024-07-27',
                    'blood_type' => 'A+',
                    'next_donation_days' => 56
                ],
                'read_at' => now()
            ]);

            // Notification système
            Notification::create([
                'user_id' => $user->id,
                'type' => 'system',
                'title' => 'Bienvenue sur BloodBank',
                'message' => 'Merci de vous être inscrit sur BloodBank. Votre compte a été créé avec succès. Vous pouvez maintenant prendre rendez-vous pour faire un don de sang.',
                'data' => [
                    'welcome' => true,
                    'account_created' => true
                ],
                'read_at' => now()
            ]);

            // Notification d'éligibilité
            Notification::create([
                'user_id' => $user->id,
                'type' => 'reminder',
                'title' => 'Vous pouvez faire un don !',
                'message' => 'Vous êtes maintenant éligible pour faire un nouveau don de sang. Votre dernier don remonte à 60 jours.',
                'data' => [
                    'last_donation_date' => '2024-05-01',
                    'days_since_last_donation' => 60,
                    'blood_type' => 'A+'
                ],
                'read_at' => null
            ]);
        }

        $this->command->info('Notifications de test créées avec succès !');
    }
}
