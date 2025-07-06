<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\BloodBank;
use App\Models\BloodStock;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envoyer une notification à un utilisateur
     */
    public function sendToUser(int $userId, string $title, string $message, string $type = 'info', array $data = [])
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false,
                'data' => $data,
            ]);

            Log::info("Notification envoyée à l'utilisateur {$userId}: {$title}");
            return true;
        } catch (\Exception $e) {
            Log::error("Erreur lors de l'envoi de notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer une notification à tous les administrateurs
     */
    public function sendToAdmins(string $title, string $message, string $type = 'info', array $data = [])
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            $this->sendToUser($admin->id, $title, $message, $type, $data);
        }
    }

    /**
     * Alerte de stock faible avec géolocalisation
     */
    public function sendLowStockAlert(BloodStock $stock, int $threshold = 10)
    {
        $bloodBank = $stock->bloodBank;
        $bloodType = $stock->bloodType;

        $title = "Alerte Stock Faible";
        $message = "Le stock de sang {$bloodType->name} est faible ({$stock->quantity_ml}ml) à {$bloodBank->name}";

        // Données pour la géolocalisation
        $data = [
            'blood_bank_id' => $bloodBank->id,
            'blood_type_id' => $bloodType->id,
            'current_stock' => $stock->quantity_ml,
            'threshold' => $threshold,
            'location' => [
                'latitude' => $bloodBank->latitude,
                'longitude' => $bloodBank->longitude,
                'address' => $bloodBank->address,
            ]
        ];

        // Notifier les administrateurs
        $this->sendToAdmins($title, $message, 'warning', $data);

        // Notifier les donneurs compatibles dans un rayon de 50km
        $this->notifyCompatibleDonors($bloodType->id, $bloodBank, $data);
    }

    /**
     * Notifier les donneurs compatibles dans un rayon donné
     */
    public function notifyCompatibleDonors(int $bloodTypeId, BloodBank $bloodBank, array $data = [], int $radiusKm = 50)
    {
        $compatibleTypes = $this->getCompatibleBloodTypes($bloodTypeId);

        $donors = User::whereHas('roles', function ($query) {
            $query->where('name', 'donor');
        })->whereHas('bloodType', function ($query) use ($compatibleTypes) {
            $query->whereIn('id', $compatibleTypes);
        })->get();

        $title = "Demande de don urgent";
        $message = "Une banque de sang proche a besoin de votre groupe sanguin. Merci de vous rendre disponible.";

        foreach ($donors as $donor) {
            // Calculer la distance (simplifié - en production, utiliser une formule Haversine)
            $distance = $this->calculateDistance(
                $bloodBank->latitude, $bloodBank->longitude,
                $donor->latitude ?? 0, $donor->longitude ?? 0
            );

            if ($distance <= $radiusKm) {
                $donorData = array_merge($data, [
                    'distance_km' => round($distance, 2),
                    'blood_bank_name' => $bloodBank->name,
                    'blood_bank_address' => $bloodBank->address,
                ]);

                $this->sendToUser($donor->id, $title, $message, 'urgent', $donorData);
            }
        }
    }

    /**
     * Notifier une demande de sang urgente
     */
    public function sendUrgentBloodRequest($bloodRequest, $patient)
    {
        $title = "Demande de sang urgente";
        $message = "Patient {$patient->name} - {$patient->bloodType->name} - Urgence: {$bloodRequest->urgency_level}";

        $data = [
            'request_id' => $bloodRequest->id,
            'patient_id' => $patient->id,
            'blood_type_id' => $patient->blood_type_id,
            'urgency_level' => $bloodRequest->urgency_level,
            'hospital_name' => $patient->hospital_name,
            'required_quantity' => $bloodRequest->required_quantity_ml,
        ];

        // Notifier les administrateurs
        $this->sendToAdmins($title, $message, 'urgent', $data);

        // Notifier les banques de sang avec stock disponible
        $this->notifyBanksWithStock($patient->blood_type_id, $bloodRequest->required_quantity_ml, $data);
    }

    /**
     * Notifier les banques de sang avec stock disponible
     */
    public function notifyBanksWithStock(int $bloodTypeId, int $requiredQuantity, array $data = [])
    {
        $banksWithStock = BloodStock::where('blood_type_id', $bloodTypeId)
            ->where('quantity_ml', '>=', $requiredQuantity)
            ->with('bloodBank')
            ->get();

        $title = "Demande de sang - Stock disponible";
        $message = "Une demande de sang urgent nécessite votre stock disponible.";

        foreach ($banksWithStock as $stock) {
            $bankData = array_merge($data, [
                'available_stock' => $stock->quantity_ml,
                'bank_name' => $stock->bloodBank->name,
            ]);

            // Notifier l'administrateur de la banque
            if ($stock->bloodBank->admin_user_id) {
                $this->sendToUser($stock->bloodBank->admin_user_id, $title, $message, 'info', $bankData);
            }
        }
    }

    /**
     * Obtenir les types de sang compatibles
     */
    private function getCompatibleBloodTypes(int $bloodTypeId): array
    {
        // Logique de compatibilité simplifiée
        $compatibility = [
            1 => [1, 5], // A+ compatible avec A+ et AB+
            2 => [2, 6], // A- compatible avec A- et AB-
            3 => [3, 7], // B+ compatible avec B+ et AB+
            4 => [4, 8], // B- compatible avec B- et AB-
            5 => [5],    // AB+ compatible avec AB+
            6 => [6],    // AB- compatible avec AB-
            7 => [1, 3, 5, 7], // O+ compatible avec A+, B+, AB+, O+
            8 => [2, 4, 6, 8], // O- compatible avec tous
        ];

        return $compatibility[$bloodTypeId] ?? [$bloodTypeId];
    }

    /**
     * Calculer la distance entre deux points (formule Haversine simplifiée)
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Rayon de la Terre en km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}