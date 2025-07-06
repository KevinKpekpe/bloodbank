<?php

namespace App\Http\Controllers;

use App\Models\Partnership;
use App\Models\BloodBank;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PartnershipController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Récupérer tous les partenariats
     */
    public function index(Request $request)
    {
        $query = Partnership::with(['requestingBank', 'respondingBank']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('requesting_bank_id')) {
            $query->where('requesting_bank_id', $request->requesting_bank_id);
        }

        if ($request->has('responding_bank_id')) {
            $query->where('responding_bank_id', $request->responding_bank_id);
        }

        $partnerships = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'partnerships' => $partnerships,
        ]);
    }

    /**
     * Demander un partenariat
     */
    public function requestPartnership(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'responding_bank_id' => 'required|exists:blood_banks,id',
            'partnership_type' => 'required|in:sharing,referral,training,research',
            'description' => 'required|string|max:1000',
            'terms' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier que l'utilisateur connecté est admin d'une banque
        $user = auth()->user();
        $requestingBank = BloodBank::where('admin_user_id', $user->id)->first();

        if (!$requestingBank) {
            return response()->json([
                'message' => 'Vous devez être administrateur d\'une banque de sang pour demander un partenariat'
            ], 403);
        }

        // Vérifier qu'il n'y a pas déjà une demande en cours
        $existingRequest = Partnership::where('requesting_bank_id', $requestingBank->id)
            ->where('responding_bank_id', $request->responding_bank_id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Une demande de partenariat existe déjà avec cette banque'
            ], 400);
        }

        DB::transaction(function () use ($request, $requestingBank) {
            $partnership = Partnership::create([
                'requesting_bank_id' => $requestingBank->id,
                'responding_bank_id' => $request->responding_bank_id,
                'partnership_type' => $request->partnership_type,
                'description' => $request->description,
                'terms' => $request->terms,
                'status' => 'pending',
            ]);

            // Notifier l'administrateur de la banque partenaire
            $respondingBank = BloodBank::find($request->responding_bank_id);
            if ($respondingBank && $respondingBank->admin_user_id) {
                $this->notificationService->sendToUser(
                    $respondingBank->admin_user_id,
                    'Nouvelle demande de partenariat',
                    "La banque {$requestingBank->name} souhaite établir un partenariat avec vous.",
                    'info',
                    [
                        'partnership_id' => $partnership->id,
                        'requesting_bank_name' => $requestingBank->name,
                        'partnership_type' => $request->partnership_type,
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Demande de partenariat envoyée avec succès',
        ]);
    }

    /**
     * Répondre à une demande de partenariat
     */
    public function respondToPartnership(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,rejected',
            'response_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $partnership = Partnership::findOrFail($id);

        // Vérifier que l'utilisateur connecté est admin de la banque qui répond
        $user = auth()->user();
        $respondingBank = BloodBank::where('admin_user_id', $user->id)->first();

        if (!$respondingBank || $respondingBank->id !== $partnership->responding_bank_id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à répondre à cette demande'
            ], 403);
        }

        if ($partnership->status !== 'pending') {
            return response()->json([
                'message' => 'Cette demande a déjà été traitée'
            ], 400);
        }

        DB::transaction(function () use ($partnership, $request) {
            $partnership->update([
                'status' => $request->status,
                'response_notes' => $request->response_notes,
                'responded_at' => now(),
            ]);

            // Notifier l'administrateur de la banque demandante
            $requestingBank = BloodBank::find($partnership->requesting_bank_id);
            if ($requestingBank && $requestingBank->admin_user_id) {
                $statusText = $request->status === 'accepted' ? 'acceptée' : 'refusée';
                $this->notificationService->sendToUser(
                    $requestingBank->admin_user_id,
                    'Réponse à votre demande de partenariat',
                    "Votre demande de partenariat avec {$respondingBank->name} a été {$statusText}.",
                    $request->status === 'accepted' ? 'success' : 'info',
                    [
                        'partnership_id' => $partnership->id,
                        'responding_bank_name' => $respondingBank->name,
                        'status' => $request->status,
                    ]
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée avec succès',
            'partnership' => $partnership->load(['requestingBank', 'respondingBank']),
        ]);
    }

    /**
     * Terminer un partenariat
     */
    public function terminatePartnership($id)
    {
        $partnership = Partnership::findOrFail($id);

        // Vérifier que l'utilisateur connecté est admin d'une des banques
        $user = auth()->user();
        $userBank = BloodBank::where('admin_user_id', $user->id)->first();

        if (!$userBank ||
            ($userBank->id !== $partnership->requesting_bank_id &&
             $userBank->id !== $partnership->responding_bank_id)) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à terminer ce partenariat'
            ], 403);
        }

        if ($partnership->status !== 'accepted') {
            return response()->json([
                'message' => 'Ce partenariat n\'est pas actif'
            ], 400);
        }

        $partnership->update([
            'status' => 'terminated',
            'terminated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Partenariat terminé avec succès',
        ]);
    }

    /**
     * Obtenir les statistiques des partenariats
     */
    public function statistics()
    {
        $totalPartnerships = Partnership::count();
        $pendingPartnerships = Partnership::where('status', 'pending')->count();
        $activePartnerships = Partnership::where('status', 'accepted')->count();
        $terminatedPartnerships = Partnership::where('status', 'terminated')->count();

        $partnershipsByType = Partnership::selectRaw('partnership_type, COUNT(*) as count')
            ->groupBy('partnership_type')
            ->get()
            ->pluck('count', 'partnership_type');

        return response()->json([
            'success' => true,
            'statistics' => [
                'total' => $totalPartnerships,
                'pending' => $pendingPartnerships,
                'active' => $activePartnerships,
                'terminated' => $terminatedPartnerships,
                'by_type' => $partnershipsByType,
            ]
        ]);
    }
}
