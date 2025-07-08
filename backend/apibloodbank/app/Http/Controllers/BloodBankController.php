<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use App\Models\BloodType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class BloodBankController extends Controller
{
    /**
     * Récupérer toutes les banques de sang actives
     */
    public function index(Request $request)
    {
        $query = BloodBank::active()->with(['admin', 'bloodStocks.bloodType']);

        // Recherche par nom
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        // Filtres
        if ($request->has('partnership_level')) {
            $query->byPartnershipLevel($request->partnership_level);
        }

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Recherche par proximité
        if ($request->has('latitude') && $request->has('longitude')) {
            $radius = $request->get('radius', 50); // Rayon par défaut 50km
            $query->nearby($request->latitude, $request->longitude, $radius);
        }

        $bloodBanks = $query->paginate(15);

        return response()->json([
            'blood_banks' => $bloodBanks
        ]);
    }

    /**
     * Récupérer une banque de sang spécifique
     */
    public function show($id)
    {
        $bloodBank = BloodBank::with(['admin', 'bloodStocks.bloodType'])->find($id);

        if (!$bloodBank) {
            return response()->json([
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        return response()->json([
            'blood_bank' => $bloodBank
        ]);
    }

    /**
     * Affiche la page publique de localisation des banques de sang
     */
    public function publicIndex(Request $request)
    {
        Log::info('=== DÉBUT publicIndex ===');

        try {
            $query = BloodBank::active()->with(['bloodStocks.bloodType']);
            Log::info('Query créée avec succès');

            // Recherche par nom
            if ($request->has('search')) {
                $search = $request->search;
                Log::info('Recherche appliquée: ' . $search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('city', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%');
                });
            }

            // Filtre par ville
            if ($request->has('city')) {
                Log::info('Filtre ville appliqué: ' . $request->city);
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            $bloodBanks = $query->orderBy('name')->paginate(15);
            Log::info('Banques récupérées: ' . $bloodBanks->count() . ' banques');

            // Log des données pour débogage
            foreach ($bloodBanks->items() as $bank) {
                Log::info("Banque: {$bank->name} - Lat: {$bank->latitude} - Lng: {$bank->longitude} - Stocks: " . $bank->bloodStocks->count());
            }

            Log::info('=== FIN publicIndex - Vue retournée ===');
            return view('blood_banks', compact('bloodBanks'));

        } catch (\Exception $e) {
            Log::error('Erreur dans publicIndex: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Recherche des banques de sang par proximité
     */
    public function searchNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_km' => 'nullable|numeric|min:1|max:500',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'search' => 'nullable|string|max:255'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radiusKm = $request->get('radius_km', 50); // Rayon par défaut 50km
        $bloodTypeId = $request->blood_type_id;
        $search = $request->search;

        // DEBUG: Récupérer toutes les banques actives sans filtrage pour diagnostiquer
        $query = BloodBank::active();

        // DEBUG: Log du nombre de banques récupérées
        $totalBanks = $query->count();
        Log::info("Total banques actives: " . $totalBanks);

        // Appliquer le filtre de proximité seulement si latitude/longitude sont fournis ET si on a des banques
        if ($latitude && $longitude && $totalBanks > 0) {
            $query = $query->nearby($latitude, $longitude, $radiusKm);
        }

        // Filtrer par type de sang si spécifié
        if ($bloodTypeId) {
            $query->whereHas('bloodStocks', function ($q) use ($bloodTypeId) {
                $q->where('blood_type_id', $bloodTypeId)
                  ->where('quantity_ml', '>', 0);
            });
        }

        $bloodBanks = $query->with(['bloodStocks.bloodType'])
            ->get();

        // DEBUG: log du nombre de banques récupérées après filtres
        Log::info("Banques après filtres: " . $bloodBanks->count());

        // Recherche textuelle simplifiée - seulement si on a un terme de recherche
        if ($search && $bloodBanks->count() > 0) {
            $searchLower = strtolower(trim($search));
            $bloodBanks = $bloodBanks->filter(function ($bank) use ($searchLower) {
                return strpos(strtolower($bank->name), $searchLower) !== false ||
                       strpos(strtolower($bank->city), $searchLower) !== false ||
                       strpos(strtolower($bank->address), $searchLower) !== false;
            });
        }

        // DEBUG: log du nombre final
        Log::info("Banques finales: " . $bloodBanks->count());

        $bloodBanks = $bloodBanks
            ->map(function ($bank) {
                return [
                    'id' => $bank->id,
                    'name' => $bank->name,
                    'address' => $bank->address,
                    'city' => $bank->city,
                    'postal_code' => $bank->postal_code,
                    'phone' => $bank->phone,
                    'email' => $bank->email,
                    'latitude' => $bank->latitude,
                    'longitude' => $bank->longitude,
                    'distance' => isset($bank->distance) ? round($bank->distance, 1) : null,
                    'stock_summary' => $bank->bloodStocks->groupBy('blood_type_id')->map(function ($items) {
                        return [
                            'total_quantity' => $items->sum('quantity_ml'),
                            'status' => $items->sum('quantity_ml') > 1000 ? 'Disponible' : 'Faible'
                        ];
                    })
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $bloodBanks,
            'count' => $bloodBanks->count(),
            'debug' => [
                'total_active' => $totalBanks,
                'search_term' => $search,
                'has_coordinates' => !empty($latitude) && !empty($longitude)
            ]
        ]);
    }

    /**
     * Créer une nouvelle banque de sang (Admin seulement)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:blood_banks',
            'website' => 'nullable|url',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'partnership_level' => 'required|in:public,private,associative',
            'admin_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodBank = BloodBank::create($request->all());

        return response()->json([
            'message' => 'Banque de sang créée avec succès',
            'blood_bank' => $bloodBank->load('admin')
        ], 201);
    }

    /**
     * Mettre à jour une banque de sang
     */
    public function update(Request $request, $id)
    {
        $bloodBank = BloodBank::find($id);

        if (!$bloodBank) {
            return response()->json([
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|unique:blood_banks,email,' . $id,
            'website' => 'nullable|url',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:10',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'partnership_level' => 'sometimes|required|in:public,private,associative',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodBank->update($request->all());

        return response()->json([
            'message' => 'Banque de sang mise à jour avec succès',
            'blood_bank' => $bloodBank->load('admin')
        ]);
    }

    /**
     * Supprimer une banque de sang (Admin seulement)
     */
    public function destroy($id)
    {
        $bloodBank = BloodBank::find($id);

        if (!$bloodBank) {
            return response()->json([
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        // Vérifier s'il y a des dons associés
        if ($bloodBank->donations()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer cette banque car elle a des dons associés'
            ], 400);
        }

        $bloodBank->delete();

        return response()->json([
            'message' => 'Banque de sang supprimée avec succès'
        ]);
    }

    /**
     * Récupérer le stock d'une banque de sang
     */
    public function getStock($id)
    {
        $bloodBank = BloodBank::with(['bloodStocks.bloodType'])->find($id);

        if (!$bloodBank) {
            return response()->json([
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        return response()->json([
            'blood_bank' => $bloodBank,
            'stock' => $bloodBank->bloodStocks,
            'total_stock' => $bloodBank->getTotalStock(),
            'has_low_stock' => $bloodBank->hasLowStock()
        ]);
    }

    /**
     * Valider une banque de sang (Admin seulement)
     */
    public function verify($id)
    {
        $bloodBank = BloodBank::find($id);

        if (!$bloodBank) {
            return response()->json([
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        $bloodBank->update(['is_verified' => true]);

        return response()->json([
            'message' => 'Banque de sang validée avec succès',
            'blood_bank' => $bloodBank
        ]);
    }

    /**
     * Dashboard d'une banque de sang : infos, admin, stocks, stats
     */
    public function dashboard($id)
    {
        $bank = BloodBank::with([
            'admin',
            'bloodStocks.bloodType',
            'donations',
            'stockMovements'
        ])->find($id);

        if (!$bank) {
            return response()->json([
                'success' => false,
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        $totalStock = $bank->getTotalStock();
        $lowStock = $bank->hasLowStock();
        $donationsCount = $bank->donations->count();
        $lastMovements = $bank->stockMovements()->latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'bank' => $bank,
                'admin' => $bank->admin,
                'blood_stocks' => $bank->bloodStocks,
                'total_stock' => $totalStock,
                'has_low_stock' => $lowStock,
                'donations_count' => $donationsCount,
                'last_stock_movements' => $lastMovements,
            ]
        ]);
    }
}
