<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\StockMovement;
use App\Models\BloodBank;
use App\Models\BloodType;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Récupérer le stock d'une banque de sang
     */
    public function index(Request $request)
    {
        $query = BloodStock::with(['bloodBank', 'bloodType']);

        if ($request->has('blood_bank_id')) {
            $query->where('blood_bank_id', $request->blood_bank_id);
        }

        if ($request->has('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        $stocks = $query->get();

        return response()->json([
            'stocks' => $stocks
        ]);
    }

    /**
     * Récupérer un stock spécifique
     */
    public function show($id)
    {
        $stock = BloodStock::with(['bloodBank', 'bloodType'])->find($id);

        if (!$stock) {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }

        return response()->json([
            'stock' => $stock
        ]);
    }

    /**
     * Mettre à jour le stock (ajustement manuel)
     */
    public function update(Request $request, $id)
    {
        $stock = BloodStock::find($id);

        if (!$stock) {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity_ml' => 'required|integer|min:0',
            'minimum_threshold' => 'required|integer|min:0',
            'maximum_capacity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Vérifier que la quantité ne dépasse pas la capacité maximale
        if ($request->quantity_ml > $request->maximum_capacity) {
            return response()->json(['message' => 'La quantité ne peut pas dépasser la capacité maximale'], 422);
        }

        // Sauvegarder l'ancienne quantité pour l'historique
        $oldQuantity = $stock->quantity_ml;

        // Mettre à jour le stock
        $stock->update([
            'quantity_ml' => $request->quantity_ml,
            'minimum_threshold' => $request->minimum_threshold,
            'maximum_capacity' => $request->maximum_capacity,
            'last_updated_by' => Auth::id()
        ]);

        // Créer un enregistrement d'historique
        $stock->history()->create([
            'old_quantity' => $oldQuantity,
            'new_quantity' => $request->quantity_ml,
            'reason' => $request->reason,
            'updated_by' => Auth::id()
        ]);

        // Vérifier si le stock est en alerte
        if ($stock->quantity_ml <= $stock->minimum_threshold) {
            // Créer une notification pour l'admin
            $bank = $stock->bloodBank;
            $bank->notifications()->create([
                'title' => 'Alerte stock faible',
                'message' => "Le stock de {$stock->bloodType->name} est faible ({$stock->quantity_ml}ml). Seuil minimum: {$stock->minimum_threshold}ml",
                'type' => 'warning',
                'user_id' => Auth::id()
            ]);
        }

        return response()->json([
            'message' => 'Stock mis à jour avec succès',
            'stock' => $stock->load(['bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Ajuster le stock (ajout ou retrait)
     */
    public function adjust(Request $request, $id)
    {
        $stock = BloodStock::find($id);

        if (!$stock) {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity_ml' => 'required|integer',
            'movement_type' => 'required|in:in,out',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier qu'on ne retire pas plus que disponible
        if ($request->movement_type === 'out' && $request->quantity_ml > $stock->quantity_ml) {
            return response()->json([
                'message' => 'Stock insuffisant pour ce retrait'
            ], 400);
        }

        $oldQuantity = $stock->quantity_ml;
        $wasLowStock = $oldQuantity <= $stock->minimum_threshold;

        DB::transaction(function () use ($stock, $request, $oldQuantity, $wasLowStock) {
            // Mettre à jour le stock
            if ($request->movement_type === 'in') {
                $stock->increment('quantity_ml', $request->quantity_ml);
            } else {
                $stock->decrement('quantity_ml', $request->quantity_ml);
            }

            $stock->update(['last_updated' => now()]);

            // Enregistrer le mouvement
            StockMovement::create([
                'blood_bank_id' => $stock->blood_bank_id,
                'blood_type_id' => $stock->blood_type_id,
                'quantity_ml' => $request->quantity_ml,
                'movement_type' => $request->movement_type,
                'reason' => $request->reason,
                'reference_id' => null,
                'reference_type' => null,
            ]);
        });

        // Recharger le stock pour avoir les données à jour
        $stock->refresh();

        // Vérifier si le stock est maintenant faible et envoyer une alerte
        if (!$wasLowStock && $stock->quantity_ml <= $stock->minimum_threshold) {
            $this->notificationService->sendLowStockAlert($stock, $stock->minimum_threshold);
        }

        return response()->json([
            'message' => 'Stock ajusté avec succès',
            'stock' => $stock->load(['bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Récupérer l'historique des mouvements de stock
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['bloodBank', 'bloodType']);

        if ($request->has('blood_bank_id')) {
            $query->where('blood_bank_id', $request->blood_bank_id);
        }

        if ($request->has('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->has('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }

        if ($request->has('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'movements' => $movements
        ]);
    }

    /**
     * Récupérer les alertes de stock faible
     */
    public function lowStockAlerts()
    {
        $lowStocks = BloodStock::with(['bloodBank', 'bloodType'])
                              ->whereRaw('quantity_ml <= minimum_threshold')
                              ->get();

        return response()->json([
            'low_stock_alerts' => $lowStocks,
            'total_alerts' => $lowStocks->count()
        ]);
    }

    /**
     * Récupérer les statistiques de stock
     */
    public function statistics(Request $request)
    {
        $query = BloodStock::with(['bloodBank', 'bloodType']);

        if ($request->has('blood_bank_id')) {
            $query->where('blood_bank_id', $request->blood_bank_id);
        }

        $stocks = $query->get();

        $statistics = [
            'total_stocks' => $stocks->count(),
            'total_quantity_ml' => $stocks->sum('quantity_ml'),
            'low_stock_count' => $stocks->where('quantity_ml', '<=', 'minimum_threshold')->count(),
            'empty_stocks' => $stocks->where('quantity_ml', 0)->count(),
            'by_blood_type' => $stocks->groupBy('blood_type.name')
                                    ->map(function ($group) {
                                        return [
                                            'total_quantity' => $group->sum('quantity_ml'),
                                            'stock_count' => $group->count(),
                                        ];
                                    }),
            'by_blood_bank' => $stocks->groupBy('blood_bank.name')
                                    ->map(function ($group) {
                                        return [
                                            'total_quantity' => $group->sum('quantity_ml'),
                                            'stock_count' => $group->count(),
                                        ];
                                    }),
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Créer un nouveau stock
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'quantity_ml' => 'required|integer|min:0',
            'minimum_threshold' => 'required|integer|min:0',
            'maximum_capacity' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Vérifier que l'utilisateur est admin de cette banque
        if (Auth::user()->role !== 'admin' || Auth::user()->blood_bank_id !== $request->blood_bank_id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        // Vérifier qu'il n'y a pas déjà un stock pour ce type de sang
        $existingStock = BloodStock::where('blood_bank_id', $request->blood_bank_id)
            ->where('blood_type_id', $request->blood_type_id)
            ->first();

        if ($existingStock) {
            return response()->json(['message' => 'Un stock existe déjà pour ce type de sang'], 422);
        }

        $stock = BloodStock::create([
            'blood_bank_id' => $request->blood_bank_id,
            'blood_type_id' => $request->blood_type_id,
            'quantity_ml' => $request->quantity_ml,
            'minimum_threshold' => $request->minimum_threshold,
            'maximum_capacity' => $request->maximum_capacity,
            'last_updated_by' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Stock créé avec succès',
            'stock' => $stock->load(['bloodBank', 'bloodType'])
        ], 201);
    }

    /**
     * Supprimer un stock
     */
    public function destroy($id)
    {
        $stock = BloodStock::find($id);

        if (!$stock) {
            return response()->json([
                'message' => 'Stock non trouvé'
            ], 404);
        }

        // Vérifier qu'il n'y a pas de quantité en stock
        if ($stock->quantity_ml > 0) {
            return response()->json([
                'message' => 'Impossible de supprimer un stock avec du contenu'
            ], 400);
        }

        $stock->delete();

        return response()->json([
            'message' => 'Stock supprimé avec succès'
        ]);
    }

    /**
     * Récupérer tous les stocks d'une banque
     */
    public function getStocks($bankId)
    {
        $bank = BloodBank::findOrFail($bankId);

        // Vérifier que l'utilisateur est admin de cette banque
        if (Auth::user()->role !== 'admin' || Auth::user()->blood_bank_id !== $bank->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $stocks = $bank->stocks()->with('bloodType')->get();

        return response()->json($stocks);
    }

    /**
     * Récupérer l'historique d'un stock
     */
    public function getHistory($stockId)
    {
        $stock = BloodStock::findOrFail($stockId);
        $bank = $stock->bloodBank;

        // Vérifier que l'utilisateur est admin de cette banque
        if (Auth::user()->role !== 'admin' || Auth::user()->blood_bank_id !== $bank->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $history = $stock->history()->with('updatedBy')->orderBy('created_at', 'desc')->get();

        return response()->json($history);
    }

    /**
     * Affiche la page de gestion des stocks (vue Blade)
     */
    public function showStocksPage()
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (!$user->role || ($user->role->name !== 'admin' && $user->role->name !== 'blood_bank')) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $bloodBanks = BloodBank::where('is_active', true)->get();
        $bloodTypes = BloodType::all();

        // Récupérer les stocks selon le rôle
        if ($user->role->name === 'admin') {
            $stocks = BloodStock::with(['bloodBank', 'bloodType'])
                ->orderBy('blood_bank_id')
                ->orderBy('blood_type_id')
                ->paginate(20);
        } else {
            // Pour les banques de sang, afficher seulement leurs stocks
            $bloodBank = BloodBank::where('admin_id', $user->id)->first();
            if (!$bloodBank) {
                return redirect()->route('dashboard')->with('error', 'Aucune banque de sang associée.');
            }

            $stocks = BloodStock::where('blood_bank_id', $bloodBank->id)
                ->with(['bloodType'])
                ->orderBy('blood_type_id')
                ->paginate(20);
        }

        // Calculer les statistiques
        $totalStocks = $stocks->total();
        $lowStockCount = $stocks->where('quantity_ml', '<', 1000)->count();
        $criticalStockCount = $stocks->where('quantity_ml', '<', 500)->count();

        $stats = [
            'totalStocks' => $totalStocks,
            'lowStockCount' => $lowStockCount,
            'criticalStockCount' => $criticalStockCount
        ];

        return view('stocks.index', compact('stocks', 'bloodBanks', 'bloodTypes', 'stats'));
    }

    /**
     * Affiche le formulaire d'ajout/modification de stock (vue Blade)
     */
    public function showStockForm($id = null)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (!$user->role || ($user->role->name !== 'admin' && $user->role->name !== 'blood_bank')) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $bloodTypes = BloodType::all();
        $stock = null;

        if ($id) {
            $stock = BloodStock::with(['bloodBank', 'bloodType'])->find($id);
            if (!$stock) {
                return redirect()->route('stocks.index')->with('error', 'Stock non trouvé.');
            }

            // Vérifier que l'utilisateur peut modifier ce stock
            if ($user->role->name === 'blood_bank') {
                $bloodBank = BloodBank::where('admin_id', $user->id)->first();
                if (!$bloodBank || $stock->blood_bank_id !== $bloodBank->id) {
                    return redirect()->route('stocks.index')->with('error', 'Accès non autorisé.');
                }
            }
        }

        // Pour les banques de sang, récupérer seulement leur banque
        if ($user->role->name === 'blood_bank') {
            $bloodBank = BloodBank::where('admin_id', $user->id)->first();
            $bloodBanks = $bloodBank ? collect([$bloodBank]) : collect();
        } else {
            $bloodBanks = BloodBank::where('is_active', true)->get();
        }

        return view('stocks.form', compact('stock', 'bloodBanks', 'bloodTypes'));
    }

    /**
     * Traite l'ajout/modification de stock (version web)
     */
    public function storeStockWeb(Request $request, $id = null)
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (!$user->role || ($user->role->name !== 'admin' && $user->role->name !== 'blood_bank')) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $request->validate([
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'quantity_ml' => 'required|numeric|min:0',
            'minimum_threshold' => 'required|numeric|min:0',
            'maximum_capacity' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        // Vérifier que l'utilisateur peut modifier cette banque
        if ($user->role->name === 'blood_bank') {
            $bloodBank = BloodBank::where('admin_id', $user->id)->first();
            if (!$bloodBank || $request->blood_bank_id != $bloodBank->id) {
                return back()->withErrors(['blood_bank_id' => 'Vous ne pouvez modifier que les stocks de votre banque.']);
            }
        }

        if ($id) {
            // Modification
            $stock = BloodStock::find($id);
            if (!$stock) {
                return redirect()->route('stocks.index')->with('error', 'Stock non trouvé.');
            }

            $stock->update($request->all());
            $message = 'Stock mis à jour avec succès.';
        } else {
            // Création
            BloodStock::create($request->all());
            $message = 'Stock créé avec succès.';
        }

        return redirect()->route('stocks.index')->with('success', $message);
    }

    /**
     * Affiche l'historique des mouvements de stock (vue Blade)
     */
    public function showMovementsPage()
    {
        $user = Auth::user();

        // Vérifier les permissions
        if (!$user->role || ($user->role->name !== 'admin' && $user->role->name !== 'blood_bank')) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
        }

        $query = StockMovement::with(['bloodBank', 'bloodType']);

        // Filtrer selon le rôle
        if ($user->role->name === 'blood_bank') {
            $bloodBank = BloodBank::where('admin_id', $user->id)->first();
            if ($bloodBank) {
                $query->where('blood_bank_id', $bloodBank->id);
            }
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('stocks.movements', compact('movements'));
    }
}
