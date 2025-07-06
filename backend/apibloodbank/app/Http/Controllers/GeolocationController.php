<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GeolocationController extends Controller
{
    /**
     * Géocoder une adresse en coordonnées
     */
    public function geocode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $response = Http::get('https://nominatim.openstreetmap.org/search', [
                'q' => $request->address,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 1,
            ]);

            if ($response->successful() && !empty($response->json())) {
                $result = $response->json()[0];

                return response()->json([
                    'success' => true,
                    'data' => [
                        'latitude' => (float) $result['lat'],
                        'longitude' => (float) $result['lon'],
                        'display_name' => $result['display_name'],
                        'address' => $result['address'] ?? [],
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Adresse non trouvée'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du géocodage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rechercher des banques de sang par proximité
     */
    public function nearbyBanks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'sometimes|integer|min:1|max:500',
            'blood_type_id' => 'sometimes|exists:blood_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $radius = $request->radius_km ?? 50; // Rayon par défaut de 50km

        $banks = BloodBank::with(['bloodStocks.bloodType'])
            ->get()
            ->map(function ($bank) use ($request) {
                $distance = $this->calculateDistance(
                    $request->latitude, $request->longitude,
                    $bank->latitude, $bank->longitude
                );

                $bank->distance_km = round($distance, 2);
                return $bank;
            })
            ->filter(function ($bank) use ($radius) {
                return $bank->distance_km <= $radius;
            })
            ->sortBy('distance_km')
            ->values();

        // Filtrer par type de sang si spécifié
        if ($request->has('blood_type_id')) {
            $banks = $banks->filter(function ($bank) use ($request) {
                return $bank->bloodStocks->where('blood_type_id', $request->blood_type_id)->isNotEmpty();
            })->values();
        }

        return response()->json([
            'success' => true,
            'banks' => $banks,
            'total_found' => $banks->count(),
            'search_radius_km' => $radius,
            'search_location' => [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        ]);
    }

    /**
     * Rechercher des donneurs par proximité
     */
    public function nearbyDonors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'sometimes|integer|min:1|max:500',
            'blood_type_id' => 'sometimes|exists:blood_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $radius = $request->radius_km ?? 50;

        $donors = User::whereHas('roles', function ($query) {
            $query->where('name', 'donor');
        })->with(['bloodType', 'donations'])
        ->get()
        ->map(function ($donor) use ($request) {
            if ($donor->latitude && $donor->longitude) {
                $distance = $this->calculateDistance(
                    $request->latitude, $request->longitude,
                    $donor->latitude, $donor->longitude
                );

                $donor->distance_km = round($distance, 2);
                return $donor;
            }
            return null;
        })
        ->filter(function ($donor) use ($radius) {
            return $donor && $donor->distance_km <= $radius;
        })
        ->sortBy('distance_km')
        ->values();

        // Filtrer par type de sang si spécifié
        if ($request->has('blood_type_id')) {
            $donors = $donors->filter(function ($donor) use ($request) {
                return $donor->blood_type_id == $request->blood_type_id;
            })->values();
        }

        return response()->json([
            'success' => true,
            'donors' => $donors,
            'total_found' => $donors->count(),
            'search_radius_km' => $radius,
        ]);
    }

    /**
     * Obtenir les statistiques de géolocalisation
     */
    public function statistics()
    {
        $totalBanks = BloodBank::count();
        $banksWithLocation = BloodBank::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->count();

        $totalDonors = User::whereHas('roles', function ($query) {
            $query->where('name', 'donor');
        })->count();

        $donorsWithLocation = User::whereHas('roles', function ($query) {
            $query->where('name', 'donor');
        })->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->count();

        return response()->json([
            'success' => true,
            'statistics' => [
                'banks' => [
                    'total' => $totalBanks,
                    'with_location' => $banksWithLocation,
                    'coverage_percentage' => $totalBanks > 0 ? round(($banksWithLocation / $totalBanks) * 100, 2) : 0,
                ],
                'donors' => [
                    'total' => $totalDonors,
                    'with_location' => $donorsWithLocation,
                    'coverage_percentage' => $totalDonors > 0 ? round(($donorsWithLocation / $totalDonors) * 100, 2) : 0,
                ],
            ]
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
