<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BloodBank;
use App\Models\BloodStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Récupérer tous les dons (avec filtres)
     */
    public function index(Request $request)
    {
        $query = Donation::with(['donor', 'bloodBank', 'bloodType']);

        // Filtres
        if ($request->has('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }

        if ($request->has('blood_bank_id')) {
            $query->where('blood_bank_id', $request->blood_bank_id);
        }

        if ($request->has('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $donations = $query->orderBy('donation_date', 'desc')->paginate(15);

        return response()->json([
            'donations' => $donations
        ]);
    }

    /**
     * Récupérer un don spécifique
     */
    public function show($id)
    {
        $donation = Donation::with(['donor', 'bloodBank', 'bloodType'])->find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Don non trouvé'
            ], 404);
        }

        return response()->json([
            'donation' => $donation
        ]);
    }

    /**
     * Créer un nouveau don
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'donor_id' => 'required|exists:users,id',
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'donation_date' => 'required|date|after_or_equal:today',
            'quantity_ml' => 'required|integer|min:200|max:500', // 200-500ml par don
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier que le donneur est éligible
        $donor = \App\Models\User::find($request->donor_id);
        if (!$donor->is_eligible_donor) {
            return response()->json([
                'message' => 'Le donneur n\'est pas éligible pour faire un don'
            ], 400);
        }

        // Vérifier que la banque de sang est active
        $bloodBank = BloodBank::find($request->blood_bank_id);
        if (!$bloodBank->is_active || !$bloodBank->is_verified) {
            return response()->json([
                'message' => 'La banque de sang n\'est pas active'
            ], 400);
        }

        $donation = Donation::create($request->all());

        return response()->json([
            'message' => 'Don programmé avec succès',
            'donation' => $donation->load(['donor', 'bloodBank', 'bloodType'])
        ], 201);
    }

    /**
     * Mettre à jour un don
     */
    public function update(Request $request, $id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Don non trouvé'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'donation_date' => 'sometimes|required|date',
            'quantity_ml' => 'sometimes|required|integer|min:200|max:500',
            'status' => 'sometimes|required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $oldStatus = $donation->status;
        $donation->update($request->all());

        // Si le statut passe à "completed", mettre à jour le stock
        if ($oldStatus !== 'completed' && $request->status === 'completed') {
            $this->updateStockFromDonation($donation);
        }

        return response()->json([
            'message' => 'Don mis à jour avec succès',
            'donation' => $donation->load(['donor', 'bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Supprimer un don
     */
    public function destroy($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Don non trouvé'
            ], 404);
        }

        // Si le don était complété, retirer du stock
        if ($donation->status === 'completed') {
            $this->removeStockFromDonation($donation);
        }

        $donation->delete();

        return response()->json([
            'message' => 'Don supprimé avec succès'
        ]);
    }

    /**
     * Marquer un don comme complété
     */
    public function complete($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Don non trouvé'
            ], 404);
        }

        if ($donation->status === 'completed') {
            return response()->json([
                'message' => 'Le don est déjà complété'
            ], 400);
        }

        if ($donation->status === 'cancelled') {
            return response()->json([
                'message' => 'Impossible de compléter un don annulé'
            ], 400);
        }

        DB::transaction(function () use ($donation) {
            $donation->update(['status' => 'completed']);
            $this->updateStockFromDonation($donation);
        });

        return response()->json([
            'message' => 'Don marqué comme complété',
            'donation' => $donation->load(['donor', 'bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Annuler un don
     */
    public function cancel($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Don non trouvé'
            ], 404);
        }

        if ($donation->status === 'cancelled') {
            return response()->json([
                'message' => 'Le don est déjà annulé'
            ], 400);
        }

        if ($donation->status === 'completed') {
            return response()->json([
                'message' => 'Impossible d\'annuler un don complété'
            ], 400);
        }

        $donation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Don annulé avec succès',
            'donation' => $donation->load(['donor', 'bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Récupérer l'historique des dons d'un donneur
     */
    public function donorHistory($donorId)
    {
        $donations = Donation::with(['bloodBank', 'bloodType'])
                            ->where('donor_id', $donorId)
                            ->orderBy('donation_date', 'desc')
                            ->get();

        return response()->json([
            'donations' => $donations,
            'total_donations' => $donations->count(),
            'total_quantity' => $donations->where('status', 'completed')->sum('quantity_ml'),
        ]);
    }

    /**
     * Récupérer les statistiques des dons
     */
    public function statistics(Request $request)
    {
        $query = Donation::query();

        // Filtres par date
        if ($request->has('date_from')) {
            $query->where('donation_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('donation_date', '<=', $request->date_to);
        }

        $statistics = [
            'total_donations' => $query->count(),
            'completed_donations' => $query->where('status', 'completed')->count(),
            'scheduled_donations' => $query->where('status', 'scheduled')->count(),
            'cancelled_donations' => $query->where('status', 'cancelled')->count(),
            'total_quantity_ml' => $query->where('status', 'completed')->sum('quantity_ml'),
            'by_blood_type' => $query->where('status', 'completed')
                                   ->join('blood_types', 'donations.blood_type_id', '=', 'blood_types.id')
                                   ->selectRaw('blood_types.name, COUNT(*) as count, SUM(donations.quantity_ml) as total_ml')
                                   ->groupBy('blood_types.id', 'blood_types.name')
                                   ->get(),
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    /**
     * Mettre à jour le stock à partir d'un don complété
     */
    private function updateStockFromDonation($donation)
    {
        // Trouver ou créer le stock pour cette banque et type de sang
        $stock = BloodStock::firstOrCreate(
            [
                'blood_bank_id' => $donation->blood_bank_id,
                'blood_type_id' => $donation->blood_type_id,
            ],
            [
                'quantity_ml' => 0,
                'minimum_threshold' => 1000,
                'maximum_capacity' => 10000,
            ]
        );

        // Mettre à jour la quantité
        $stock->increment('quantity_ml', $donation->quantity_ml);
        $stock->update(['last_updated' => now()]);

        // Enregistrer le mouvement de stock
        StockMovement::create([
            'blood_bank_id' => $donation->blood_bank_id,
            'blood_type_id' => $donation->blood_type_id,
            'quantity_ml' => $donation->quantity_ml,
            'movement_type' => 'in',
            'reason' => 'Don de sang',
            'reference_id' => $donation->id,
            'reference_type' => 'App\Models\Donation',
        ]);
    }

    /**
     * Retirer le stock d'un don supprimé
     */
    private function removeStockFromDonation($donation)
    {
        $stock = BloodStock::where('blood_bank_id', $donation->blood_bank_id)
                          ->where('blood_type_id', $donation->blood_type_id)
                          ->first();

        if ($stock) {
            $stock->decrement('quantity_ml', $donation->quantity_ml);
            $stock->update(['last_updated' => now()]);

            // Enregistrer le mouvement de stock
            StockMovement::create([
                'blood_bank_id' => $donation->blood_bank_id,
                'blood_type_id' => $donation->blood_type_id,
                'quantity_ml' => $donation->quantity_ml,
                'movement_type' => 'out',
                'reason' => 'Suppression de don',
                'reference_id' => $donation->id,
                'reference_type' => 'App\Models\Donation',
            ]);
        }
    }
}
