<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BloodBank;
use App\Models\BloodStock;
use App\Models\Patient;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Contact;
use App\Models\Partnership;
use Illuminate\Support\Facades\Hash;

class DocumentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs d'exemple pour chaque rôle
        $admin = User::create([
            'name' => 'Admin Documentation',
            'email' => 'admin@bloodbank.com',
            'password' => Hash::make('password'),
            'phone' => '+33123456789',
            'address' => '123 Rue de la Paix, Paris',
            'city' => 'Paris',
            'postal_code' => '75001',
            'country' => 'France',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
            'blood_type_id' => 1,
        ]);
        $admin->roles()->attach(1); // Admin

        $donor = User::create([
            'name' => 'Jean Donneur',
            'email' => 'donor@example.com',
            'password' => Hash::make('password'),
            'phone' => '+33123456790',
            'address' => '456 Avenue des Dons, Lyon',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'country' => 'France',
            'latitude' => 45.7578,
            'longitude' => 4.8320,
            'blood_type_id' => 7, // O+
        ]);
        $donor->roles()->attach(2); // Donor

        $doctor = User::create([
            'name' => 'Dr. Marie Médecin',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'phone' => '+33123456791',
            'address' => '789 Boulevard de la Santé, Marseille',
            'city' => 'Marseille',
            'postal_code' => '13001',
            'country' => 'France',
            'latitude' => 43.2965,
            'longitude' => 5.3698,
            'blood_type_id' => 3,
        ]);
        $doctor->roles()->attach(4); // Doctor

        // Créer des banques de sang d'exemple
        $bank1 = BloodBank::create([
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
            'admin_user_id' => $admin->id,
        ]);

        $bank2 = BloodBank::create([
            'name' => 'Établissement Français du Sang Lyon',
            'description' => 'Centre régional de transfusion sanguine',
            'phone' => '04 72 68 85 00',
            'email' => 'contact@efs-lyon.fr',
            'website' => 'https://www.efs-lyon.fr',
            'address' => '1 rue du Professeur Jean Dausset',
            'city' => 'Lyon',
            'postal_code' => '69007',
            'country' => 'France',
            'latitude' => 45.7578,
            'longitude' => 4.8320,
            'is_verified' => true,
            'is_active' => true,
            'partnership_level' => 'public',
            'admin_user_id' => $admin->id,
        ]);

        // Créer des stocks d'exemple
        BloodStock::create([
            'blood_bank_id' => $bank1->id,
            'blood_type_id' => 1, // A+
            'quantity_ml' => 5000,
            'minimum_threshold' => 1000,
            'maximum_capacity' => 10000,
            'last_updated' => now(),
        ]);

        BloodStock::create([
            'blood_bank_id' => $bank1->id,
            'blood_type_id' => 7, // O+
            'quantity_ml' => 8000,
            'minimum_threshold' => 2000,
            'maximum_capacity' => 15000,
            'last_updated' => now(),
        ]);

        BloodStock::create([
            'blood_bank_id' => $bank2->id,
            'blood_type_id' => 1, // A+
            'quantity_ml' => 3000,
            'minimum_threshold' => 1000,
            'maximum_capacity' => 8000,
            'last_updated' => now(),
        ]);

        // Créer des patients d'exemple
        $patient1 = Patient::create([
            'name' => 'Marie Dupont',
            'email' => 'marie.dupont@example.com',
            'phone' => '+33123456792',
            'blood_type_id' => 1, // A+
            'date_of_birth' => '1985-03-15',
            'gender' => 'female',
            'hospital_name' => 'Hôpital de la Pitié-Salpêtrière',
            'hospital_address' => '47-83 Boulevard de l\'Hôpital, Paris',
            'latitude' => 48.8361,
            'longitude' => 2.3614,
            'urgency_level' => 'medium',
            'medical_notes' => 'Patient nécessitant des transfusions régulières',
        ]);

        $patient2 = Patient::create([
            'name' => 'Pierre Martin',
            'email' => 'pierre.martin@example.com',
            'phone' => '+33123456793',
            'blood_type_id' => 7, // O+
            'date_of_birth' => '1978-07-22',
            'gender' => 'male',
            'hospital_name' => 'Hôpital Edouard Herriot',
            'hospital_address' => '5 Place d\'Arsonval, Lyon',
            'latitude' => 45.7578,
            'longitude' => 4.8320,
            'urgency_level' => 'high',
            'medical_notes' => 'Urgence chirurgicale',
        ]);

        // Créer des demandes de sang d'exemple
        BloodRequest::create([
            'patient_id' => $patient1->id,
            'blood_type_id' => 1, // A+
            'required_quantity_ml' => 500,
            'urgency_level' => 'medium',
            'status' => 'pending',
            'contact_phone' => '+33123456792',
            'notes' => 'Transfusion programmée pour le 15/07/2025',
            'is_emergency' => false,
        ]);

        BloodRequest::create([
            'patient_id' => $patient2->id,
            'blood_type_id' => 7, // O+
            'required_quantity_ml' => 1000,
            'urgency_level' => 'critical',
            'status' => 'pending',
            'contact_phone' => '+33123456793',
            'notes' => 'URGENCE - Chirurgie cardiaque',
            'is_emergency' => true,
        ]);

        // Créer des dons d'exemple
        Donation::create([
            'donor_id' => $donor->id,
            'blood_bank_id' => $bank1->id,
            'blood_type_id' => 7, // O+
            'donation_date' => now()->subDays(5),
            'quantity_ml' => 450,
            'status' => 'completed',
            'notes' => 'Don régulier',
        ]);

        // Créer des contacts d'exemple
        Contact::create([
            'name' => 'Sophie Contact',
            'email' => 'sophie.contact@example.com',
            'subject' => 'Demande d\'information sur les dons',
            'message' => 'Bonjour, j\'aimerais avoir des informations sur les conditions pour donner mon sang.',
            'phone' => '+33123456794',
            'contact_type' => 'general',
            'status' => 'pending',
        ]);

        Contact::create([
            'name' => 'Association Don de Sang',
            'email' => 'contact@association-don-sang.fr',
            'subject' => 'Demande de partenariat',
            'message' => 'Nous souhaiterions établir un partenariat pour organiser des collectes de sang.',
            'phone' => '+33123456795',
            'contact_type' => 'partnership',
            'status' => 'pending',
        ]);

        // Créer un partenariat d'exemple
        Partnership::create([
            'requesting_bank_id' => $bank1->id,
            'responding_bank_id' => $bank2->id,
            'partnership_type' => 'sharing',
            'description' => 'Partage de stocks en cas d\'urgence',
            'terms' => 'Accord de partage mutuel des stocks en cas de pénurie',
            'status' => 'accepted',
            'response_notes' => 'Partenariat accepté avec enthousiasme',
            'responded_at' => now()->subDays(10),
        ]);

        $this->command->info('Données d\'exemple pour la documentation créées avec succès !');
    }
}
