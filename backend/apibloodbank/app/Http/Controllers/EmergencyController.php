<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Patient;
use App\Models\BloodBank;
use App\Models\BloodStock;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmergencyController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Déclarer une urgence
     */
    public function declare(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'required_quantity_ml' => 'required|integer|min:100',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'hospital_name' => 'required|string|max:255',
            'hospital_address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'contact_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::transaction(function () use ($request) {
            // Créer ou mettre à jour le patient
            $patient = Patient::updateOrCreate(
                ['id' => $request->patient_id],
                [
                    'blood_type_id' => $request->blood_type_id,
                    'hospital_name' => $request->hospital_name,
                    'hospital_address' => $request->hospital_address,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'urgency_level' => $request->urgency_level,
                ]
            );

            // Créer la demande d'urgence
            $bloodRequest = BloodRequest::create([
                'patient_id' => $patient->id,
                'blood_type_id' => $request->blood_type_id,
                'required_quantity_ml' => $request->required_quantity_ml,
                'urgency_level' => $request->urgency_level,
                'status' => 'pending',
                'contact_phone' => $request->contact_phone,
                'notes' => $request->notes,
                'is_emergency' => true,
            ]);

            // Envoyer les notifications d'urgence
            $this->notificationService->sendUrgentBloodRequest($bloodRequest, $patient);
        });

        return response()->json([
            'success' => true,
            'message' => 'Urgence déclarée avec succès. Les notifications ont été envoyées.',
        ]);
    }

    /**
     * Rechercher des banques de sang disponibles pour une urgence
     */
    public function findAvailableBanks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_type_id' => 'required|exists:blood_types,id',
            'required_quantity_ml' => 'required|integer|min:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'sometimes|integer|min:1|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $radius = $request->radius_km ?? 100; // Rayon par défaut de 100km

        // Rechercher les banques avec stock disponible dans le rayon
        $availableBanks = BloodStock::where('blood_type_id', $request->blood_type_id)
            ->where('quantity_ml', '>=', $request->required_quantity_ml)
            ->with(['bloodBank', 'bloodType'])
            ->get()
            ->filter(function ($stock) use ($request, $radius) {
                $distance = $this->calculateDistance(
                    $request->latitude, $request->longitude,
                    $stock->bloodBank->latitude, $stock->bloodBank->longitude
                );

                $stock->bloodBank->distance_km = round($distance, 2);
                return $distance <= $radius;
            })
            ->sortBy('bloodBank.distance_km')
            ->values();

        return response()->json([
            'success' => true,
            'available_banks' => $availableBanks,
            'total_found' => $availableBanks->count(),
            'search_radius_km' => $radius,
        ]);
    }

    /**
     * Obtenir les urgences actives
     */
    public function activeEmergencies()
    {
        $emergencies = BloodRequest::where('is_emergency', true)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['patient', 'bloodType', 'fulfillments.bloodBank'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'active_emergencies' => $emergencies,
            'total_active' => $emergencies->count(),
        ]);
    }

    /**
     * Mettre à jour le statut d'une urgence
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,fulfilled,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $emergency = BloodRequest::where('is_emergency', true)->findOrFail($id);
        $emergency->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $emergency->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de l\'urgence mis à jour',
            'emergency' => $emergency->load(['patient', 'bloodType']),
        ]);
    }

    /**
     * Calculer la distance entre deux points (formule Haversine)
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
