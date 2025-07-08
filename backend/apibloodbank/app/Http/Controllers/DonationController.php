<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BloodBank;
use App\Models\BloodStock;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
     * Récupérer l'historique des dons de l'utilisateur connecté
     */
    public function history()
    {
        $user = Auth::user();

        $donations = Donation::where('donor_id', $user->id)
            ->with(['bloodBank', 'bloodType'])
            ->orderBy('donation_date', 'desc')
            ->get()
            ->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'blood_bank_name' => $donation->bloodBank->name,
                    'donation_date' => $donation->donation_date,
                    'donation_type' => $donation->donation_type ?? 'Sang total',
                    'status' => $donation->status,
                    'quantity_ml' => $donation->quantity_ml,
                    'blood_type' => $donation->bloodType->name ?? 'Non spécifié'
                ];
            });

        return response()->json([
            'data' => $donations
        ]);
    }

    /**
     * Récupérer les statistiques du donneur
     */
    public function stats()
    {
        $user = Auth::user();

        $totalDonations = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $lastDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('donation_date', 'desc')
            ->first();

        $nextDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'scheduled')
            ->orderBy('donation_date', 'asc')
            ->first();

        // Vérifier l'éligibilité
        $eligible = $this->checkEligibility($user);

        return response()->json([
            'data' => [
                'total_donations' => $totalDonations,
                'last_donation' => $lastDonation ? $lastDonation->donation_date : null,
                'next_donation' => $nextDonation ? $nextDonation->donation_date : null,
                'eligible' => $eligible
            ]
        ]);
    }

    /**
     * Vérifier l'éligibilité du donneur
     */
    public function eligibility()
    {
        $user = Auth::user();
        $eligible = $this->checkEligibility($user);

        return response()->json([
            'data' => [
                'eligible' => $eligible,
                'reasons' => $this->getEligibilityReasons($user)
            ]
        ]);
    }

    /**
     * Prendre un rendez-vous de don
     */
    public function bookAppointment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'donation_type' => 'required|in:whole_blood,plasma,platelets',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Vérifier l'éligibilité
        if (!$this->checkEligibility($user)) {
            return response()->json([
                'message' => 'Vous n\'êtes pas éligible pour faire un don à ce moment'
            ], 400);
        }

        // Vérifier la disponibilité
        if (!$this->checkAvailability($request->blood_bank_id, $request->preferred_date, $request->preferred_time)) {
            return response()->json([
                'message' => 'Créneau non disponible'
            ], 400);
        }

        // Créer le rendez-vous
        $donation = Donation::create([
            'donor_id' => $user->id,
            'blood_bank_id' => $request->blood_bank_id,
            'donation_date' => $request->preferred_date . ' ' . $request->preferred_time,
            'donation_type' => $request->donation_type,
            'status' => 'scheduled',
            'notes' => $request->notes,
            'quantity_ml' => $this->getDefaultQuantity($request->donation_type)
        ]);

        return response()->json([
            'message' => 'Rendez-vous réservé avec succès',
            'data' => $donation->load(['bloodBank'])
        ], 201);
    }

    /**
     * Annuler un rendez-vous
     */
    public function cancelAppointment($id)
    {
        $user = Auth::user();

        $donation = Donation::where('id', $id)
            ->where('donor_id', $user->id)
            ->where('status', 'scheduled')
            ->first();

        if (!$donation) {
            return response()->json([
                'message' => 'Rendez-vous non trouvé ou non annulable'
            ], 404);
        }

        $donation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Rendez-vous annulé avec succès'
        ]);
    }

    /**
     * Récupérer les disponibilités d'une banque de sang
     */
    public function availability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $bookedSlots = Donation::where('blood_bank_id', $request->blood_bank_id)
            ->whereDate('donation_date', $request->date)
            ->where('status', 'scheduled')
            ->pluck('donation_date')
            ->map(function ($date) {
                return date('H:i', strtotime($date));
            })
            ->toArray();

        $availableSlots = ['09:00', '10:00', '11:00', '14:00', '15:00', '16:00'];
        $availableSlots = array_diff($availableSlots, $bookedSlots);

        return response()->json([
            'data' => [
                'date' => $request->date,
                'available_slots' => array_values($availableSlots),
                'booked_slots' => $bookedSlots
            ]
        ]);
    }

    /**
     * Récupérer les types de don disponibles
     */
    public function types()
    {
        $types = [
            [
                'id' => 'whole_blood',
                'name' => 'Sang total',
                'description' => 'Don de sang complet',
                'duration' => '10-15 minutes',
                'frequency' => '56 jours'
            ],
            [
                'id' => 'plasma',
                'name' => 'Plasma',
                'description' => 'Don de plasma uniquement',
                'duration' => '45-60 minutes',
                'frequency' => '14 jours'
            ],
            [
                'id' => 'platelets',
                'name' => 'Plaquettes',
                'description' => 'Don de plaquettes uniquement',
                'duration' => '60-90 minutes',
                'frequency' => '7 jours'
            ]
        ];

        return response()->json([
            'data' => $types
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

        $donation->update(['status' => 'completed']);
        $this->updateStockFromDonation($donation);

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

        // Si le don était complété, retirer du stock
        if ($donation->status === 'completed') {
            $this->removeStockFromDonation($donation);
        }

        $donation->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Don annulé avec succès',
            'donation' => $donation->load(['donor', 'bloodBank', 'bloodType'])
        ]);
    }

    /**
     * Historique des dons d'un donneur spécifique
     */
    public function donorHistory($donorId)
    {
        $donations = Donation::where('donor_id', $donorId)
            ->with(['bloodBank', 'bloodType'])
            ->orderBy('donation_date', 'desc')
            ->get();

        return response()->json([
            'donations' => $donations
        ]);
    }

    /**
     * Statistiques des dons
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

        $stats = [
            'total_donations' => $query->count(),
            'completed_donations' => $query->where('status', 'completed')->count(),
            'scheduled_donations' => $query->where('status', 'scheduled')->count(),
            'cancelled_donations' => $query->where('status', 'cancelled')->count(),
            'total_quantity_ml' => $query->where('status', 'completed')->sum('quantity_ml'),
            'donations_by_type' => $query->where('status', 'completed')
                ->selectRaw('donation_type, COUNT(*) as count')
                ->groupBy('donation_type')
                ->get()
        ];

        return response()->json([
            'statistics' => $stats
        ]);
    }

    /**
     * Vérifier l'éligibilité d'un utilisateur
     */
    private function checkEligibility($user)
    {
        // Vérifier l'âge (18-70 ans)
        $age = date_diff(date_create($user->birth_date), date_create('today'))->y;
        if ($age < 18 || $age > 70) {
            return false;
        }

        // Vérifier le poids (minimum 50kg)
        if ($user->weight && $user->weight < 50) {
            return false;
        }

        // Vérifier le dernier don (minimum 56 jours)
        $lastDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('donation_date', 'desc')
            ->first();

        if ($lastDonation) {
            $daysSinceLastDonation = date_diff(
                date_create($lastDonation->donation_date),
                date_create('today')
            )->days;

            if ($daysSinceLastDonation < 56) {
                return false;
            }
        }

        // Vérifier la santé générale
        if (!$user->is_eligible_donor) {
            return false;
        }

        return true;
    }

    /**
     * Obtenir les raisons d'inéligibilité
     */
    private function getEligibilityReasons($user)
    {
        $reasons = [];

        // Vérifier l'âge
        $age = date_diff(date_create($user->birth_date), date_create('today'))->y;
        if ($age < 18) {
            $reasons[] = 'Âge insuffisant (minimum 18 ans)';
        } elseif ($age > 70) {
            $reasons[] = 'Âge trop élevé (maximum 70 ans)';
        }

        // Vérifier le poids
        if ($user->weight && $user->weight < 50) {
            $reasons[] = 'Poids insuffisant (minimum 50 kg)';
        }

        // Vérifier le dernier don
        $lastDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('donation_date', 'desc')
            ->first();

        if ($lastDonation) {
            $daysSinceLastDonation = date_diff(
                date_create($lastDonation->donation_date),
                date_create('today')
            )->days;

            if ($daysSinceLastDonation < 56) {
                $reasons[] = 'Délai insuffisant depuis le dernier don (minimum 56 jours)';
            }
        }

        // Vérifier la santé générale
        if (!$user->is_eligible_donor) {
            $reasons[] = 'Problème de santé détecté';
        }

        return $reasons;
    }

    /**
     * Vérifier la disponibilité d'un créneau
     */
    private function checkAvailability($bloodBankId, $date, $time)
    {
        $existingDonation = Donation::where('blood_bank_id', $bloodBankId)
            ->whereDate('donation_date', $date)
            ->whereTime('donation_date', $time)
            ->where('status', 'scheduled')
            ->exists();

        return !$existingDonation;
    }

    /**
     * Obtenir la quantité par défaut selon le type de don
     */
    private function getDefaultQuantity($donationType)
    {
        $quantities = [
            'whole_blood' => 450,
            'plasma' => 600,
            'platelets' => 200
        ];

        return $quantities[$donationType] ?? 450;
    }

    /**
     * Mettre à jour le stock après un don complété
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
     * Retirer du stock après annulation d'un don complété
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

    /**
     * Affiche la page de gestion des dons (vue Blade)
     */
    public function showDonationsPage()
    {
        $user = Auth::user();
        $donations = Donation::where('donor_id', $user->id)
            ->with(['bloodBank', 'bloodType'])
            ->orderBy('donation_date', 'desc')
            ->paginate(10);

        $bloodBanks = BloodBank::where('is_active', true)->get();
        $bloodTypes = \App\Models\BloodType::all();

        // Calculer les statistiques
        $totalDonations = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $lastDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('donation_date', 'desc')
            ->first();

        $nextDonation = Donation::where('donor_id', $user->id)
            ->where('status', 'scheduled')
            ->orderBy('donation_date', 'asc')
            ->first();

        $eligible = $this->checkEligibility($user);

        $stats = [
            'totalDonations' => $totalDonations,
            'lastDonation' => $lastDonation ? \Carbon\Carbon::parse($lastDonation->donation_date)->format('d/m/Y') : 'Aucun',
            'nextDonation' => $nextDonation ? \Carbon\Carbon::parse($nextDonation->donation_date)->format('d/m/Y') : 'Non planifié',
            'eligible' => $eligible
        ];

        return view('donations.index', compact('donations', 'bloodBanks', 'bloodTypes', 'stats'));
    }

    /**
     * Affiche le formulaire de prise de rendez-vous (vue Blade)
     */
    public function showBookingForm()
    {
        $bloodBanks = BloodBank::where('is_active', true)->get();
        $bloodTypes = \App\Models\BloodType::all();

        return view('donations.book', compact('bloodBanks', 'bloodTypes'));
    }

    /**
     * Traite la prise de rendez-vous (version web)
     */
    public function bookAppointmentWeb(Request $request)
    {
        $request->validate([
            'blood_bank_id' => 'required|exists:blood_banks,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|string',
            'donation_type' => 'required|in:whole_blood,plasma,platelets',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();

        // Vérifier l'éligibilité
        if (!$this->checkEligibility($user)) {
            return back()->withErrors(['eligibility' => 'Vous n\'êtes pas éligible pour faire un don à ce moment.']);
        }

        // Vérifier la disponibilité
        if (!$this->checkAvailability($request->blood_bank_id, $request->preferred_date, $request->preferred_time)) {
            return back()->withErrors(['availability' => 'Créneau non disponible.']);
        }

        // Créer le rendez-vous
        $donation = Donation::create([
            'donor_id' => $user->id,
            'blood_bank_id' => $request->blood_bank_id,
            'donation_date' => $request->preferred_date . ' ' . $request->preferred_time,
            'donation_type' => $request->donation_type,
            'status' => 'scheduled',
            'notes' => $request->notes,
            'quantity_ml' => $this->getDefaultQuantity($request->donation_type)
        ]);

        return redirect()->route('donations.index')->with('success', 'Rendez-vous réservé avec succès !');
    }

    /**
     * Annule un rendez-vous (version web)
     */
    public function cancelAppointmentWeb($id)
    {
        $user = Auth::user();

        $donation = Donation::where('id', $id)
            ->where('donor_id', $user->id)
            ->where('status', 'scheduled')
            ->first();

        if (!$donation) {
            return back()->withErrors(['donation' => 'Rendez-vous non trouvé ou déjà annulé.']);
        }

        $donation->update(['status' => 'cancelled']);

        return redirect()->route('donations.index')->with('success', 'Rendez-vous annulé avec succès.');
    }
}
