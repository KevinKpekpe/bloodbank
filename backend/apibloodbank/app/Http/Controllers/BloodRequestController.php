<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Patient;
use App\Models\BloodBank;
use App\Models\BloodStock;
use App\Models\RequestFulfillment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BloodRequestController extends Controller
{
    /**
     * Récupérer toutes les demandes de sang (avec filtres)
     */
    public function index(Request $request)
    {
        $query = BloodRequest::with(['patient', 'bloodType', 'fulfillments.bloodBank']);

        // Filtres
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('blood_type_id')) {
            $query->where('blood_type_id', $request->blood_type_id);
        }

        if ($request->has('urgency_level')) {
            $query->where('urgency_level', $request->urgency_level);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->where('requested_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('requested_date', '<=', $request->date_to);
        }

        // Tri par urgence et date
        $query->orderByRaw("CASE
            WHEN urgency_level = 'critical' THEN 1
            WHEN urgency_level = 'urgent' THEN 2
            ELSE 3
        END")
        ->orderBy('requested_date', 'desc');

        $bloodRequests = $query->paginate(15);

        return response()->json([
            'blood_requests' => $bloodRequests
        ]);
    }

    /**
     * Récupérer une demande de sang spécifique
     */
    public function show($id)
    {
        $bloodRequest = BloodRequest::with(['patient', 'bloodType', 'fulfillments.bloodBank'])->find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        return response()->json([
            'blood_request' => $bloodRequest
        ]);
    }

    /**
     * Créer une nouvelle demande de sang
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'blood_type_id' => 'required|exists:blood_types,id',
            'quantity_ml' => 'required|integer|min:100|max:5000', // 100ml à 5L
            'urgency_level' => 'required|in:normal,urgent,critical',
            'requested_date' => 'required|date|after_or_equal:today',
            'needed_by_date' => 'nullable|date|after:requested_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodRequest = BloodRequest::create($request->all());

        return response()->json([
            'message' => 'Demande de sang créée avec succès',
            'blood_request' => $bloodRequest->load(['patient', 'bloodType'])
        ], 201);
    }

    /**
     * Mettre à jour une demande de sang
     */
    public function update(Request $request, $id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'quantity_ml' => 'sometimes|required|integer|min:100|max:5000',
            'urgency_level' => 'sometimes|required|in:normal,urgent,critical',
            'status' => 'sometimes|required|in:pending,approved,fulfilled,cancelled',
            'requested_date' => 'sometimes|required|date',
            'needed_by_date' => 'nullable|date|after:requested_date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bloodRequest->update($request->all());

        return response()->json([
            'message' => 'Demande de sang mise à jour avec succès',
            'blood_request' => $bloodRequest->load(['patient', 'bloodType'])
        ]);
    }

    /**
     * Supprimer une demande de sang
     */
    public function destroy($id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        // Vérifier s'il y a des satisfactions associées
        if ($bloodRequest->fulfillments()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer cette demande car elle a des satisfactions associées'
            ], 400);
        }

        $bloodRequest->delete();

        return response()->json([
            'message' => 'Demande de sang supprimée avec succès'
        ]);
    }

    /**
     * Rechercher des disponibilités pour une demande
     */
    public function searchAvailability($id)
    {
        $bloodRequest = BloodRequest::with(['patient', 'bloodType'])->find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        // Rechercher les banques avec du stock disponible
        $availableStocks = BloodStock::with(['bloodBank', 'bloodType'])
                                    ->where('blood_type_id', $bloodRequest->blood_type_id)
                                    ->where('quantity_ml', '>=', $bloodRequest->quantity_ml)
                                    ->whereHas('bloodBank', function ($query) {
                                        $query->where('is_active', true)
                                              ->where('is_verified', true);
                                    })
                                    ->get();

        // Calculer les distances si on a les coordonnées du patient
        $patient = $bloodRequest->patient;
        if ($patient && $patient->hospital_name) {
            // Ici on pourrait intégrer un service de géocodage pour obtenir les coordonnées de l'hôpital
            // Pour l'instant, on retourne les stocks disponibles
        }

        return response()->json([
            'blood_request' => $bloodRequest,
            'available_stocks' => $availableStocks,
            'total_available_ml' => $availableStocks->sum('quantity_ml'),
            'can_fulfill' => $availableStocks->sum('quantity_ml') >= $bloodRequest->quantity_ml
        ]);
    }

    /**
     * Approuver une demande de sang
     */
    public function approve($id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        if ($bloodRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Cette demande ne peut pas être approuvée'
            ], 400);
        }

        $bloodRequest->update(['status' => 'approved']);

        return response()->json([
            'message' => 'Demande de sang approuvée',
            'blood_request' => $bloodRequest->load(['patient', 'bloodType'])
        ]);
    }

    /**
     * Annuler une demande de sang
     */
    public function cancel($id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        if ($bloodRequest->status === 'fulfilled') {
            return response()->json([
                'message' => 'Impossible d\'annuler une demande satisfaite'
            ], 400);
        }

        $bloodRequest->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Demande de sang annulée',
            'blood_request' => $bloodRequest->load(['patient', 'bloodType'])
        ]);
    }

    /**
     * Satisfaire une demande de sang
     */
    public function fulfill(Request $request, $id)
    {
        $bloodRequest = BloodRequest::find($id);

        if (!$bloodRequest) {
            return response()->json([
                'message' => 'Demande de sang non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'quantity_ml' => 'required|integer|min:1|max:' . $bloodRequest->quantity_ml,
            'fulfillment_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier que la banque a assez de stock
        $stock = BloodStock::where('blood_bank_id', $request->blood_bank_id)
                          ->where('blood_type_id', $bloodRequest->blood_type_id)
                          ->first();

        if (!$stock || $stock->quantity_ml < $request->quantity_ml) {
            return response()->json([
                'message' => 'Stock insuffisant dans cette banque de sang'
            ], 400);
        }

        DB::transaction(function () use ($bloodRequest, $request, $stock) {
            // Créer la satisfaction
            RequestFulfillment::create([
                'blood_request_id' => $bloodRequest->id,
                'blood_bank_id' => $request->blood_bank_id,
                'quantity_ml' => $request->quantity_ml,
                'fulfillment_date' => $request->fulfillment_date,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Retirer du stock
            $stock->decrement('quantity_ml', $request->quantity_ml);
            $stock->update(['last_updated' => now()]);

            // Mettre à jour le statut de la demande si complètement satisfaite
            $totalFulfilled = $bloodRequest->fulfillments()->sum('quantity_ml') + $request->quantity_ml;
            if ($totalFulfilled >= $bloodRequest->quantity_ml) {
                $bloodRequest->update(['status' => 'fulfilled']);
            }
        });

        return response()->json([
            'message' => 'Demande de sang satisfaite',
            'blood_request' => $bloodRequest->load(['patient', 'bloodType', 'fulfillments.bloodBank'])
        ]);
    }

    /**
     * Récupérer les statistiques des demandes
     */
    public function statistics(Request $request)
    {
        $query = BloodRequest::query();

        if ($request->has('date_from')) {
            $query->where('requested_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('requested_date', '<=', $request->date_to);
        }

        $statistics = [
            'total_requests' => $query->count(),
            'pending_requests' => $query->where('status', 'pending')->count(),
            'approved_requests' => $query->where('status', 'approved')->count(),
            'fulfilled_requests' => $query->where('status', 'fulfilled')->count(),
            'cancelled_requests' => $query->where('status', 'cancelled')->count(),
            'total_quantity_ml' => $query->sum('quantity_ml'),
            'by_urgency_level' => $query->selectRaw('urgency_level, COUNT(*) as count, SUM(quantity_ml) as total_ml')
                                      ->groupBy('urgency_level')
                                      ->get(),
            'by_blood_type' => $query->join('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
                                   ->selectRaw('blood_types.name, COUNT(*) as count, SUM(blood_requests.quantity_ml) as total_ml')
                                   ->groupBy('blood_types.id', 'blood_types.name')
                                   ->get(),
        ];

        return response()->json([
            'statistics' => $statistics
        ]);
    }
}
