<?php

namespace App\Http\Controllers;

use App\Models\BloodBank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BloodBankRegistrationController extends Controller
{
    /**
     * Affiche le formulaire d'enregistrement d'une banque de sang
     */
    public function showRegistrationForm()
    {
        $partnershipLevels = [
            'public' => 'Public',
            'private' => 'Privé',
            'associative' => 'Associatif',
        ];
        return view('blood_bank_register', compact('partnershipLevels'));
    }

    /**
     * Enregistre une nouvelle banque de sang avec son administrateur
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Données de la banque de sang
            'bank_name' => 'required|string|max:255',
            'bank_description' => 'nullable|string',
            'bank_phone' => 'required|string|max:20',
            'bank_email' => 'required|email|unique:blood_banks,email',
            'bank_website' => 'nullable|url',
            'bank_address' => 'required|string',
            'bank_city' => 'required|string|max:100',
            'bank_postal_code' => 'required|string|max:10',
            'bank_country' => 'nullable|string|max:100',
            'bank_latitude' => 'nullable|numeric|between:-90,90',
            'bank_longitude' => 'nullable|numeric|between:-180,180',
            'bank_partnership_level' => 'required|in:public,private,associative',

            // Données de l'administrateur
            'admin_first_name' => 'required|string|max:255',
            'admin_last_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_phone' => 'required|string|max:20',
            'admin_password' => 'required|string|min:8|confirmed',
            'admin_password_confirmation' => 'required|string|min:8',
        ], [
            'bank_email.unique' => 'Cette adresse email est déjà utilisée par une autre banque de sang.',
            'admin_email.unique' => 'Cette adresse email est déjà utilisée par un autre utilisateur.',
            'admin_password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Récupérer le rôle blood_bank
            $bloodBankRole = \App\Models\Role::where('name', 'blood_bank')->first();
            if (!$bloodBankRole) {
                throw new \Exception('Rôle blood_bank non trouvé');
            }

            // Créer l'administrateur
            $admin = User::create([
                'name' => $request->admin_first_name . ' ' . $request->admin_last_name,
                'email' => $request->admin_email,
                'phone' => $request->admin_phone,
                'password' => Hash::make($request->admin_password),
                'role_id' => $bloodBankRole->id,
                'email_verified_at' => now(), // Auto-vérification pour les admins de banque
            ]);

            // Créer la banque de sang
            $bloodBank = BloodBank::create([
                'name' => $request->bank_name,
                'description' => $request->bank_description,
                'phone' => $request->bank_phone,
                'email' => $request->bank_email,
                'website' => $request->bank_website,
                'address' => $request->bank_address,
                'city' => $request->bank_city,
                'postal_code' => $request->bank_postal_code,
                'country' => $request->bank_country ?? 'France',
                'latitude' => $request->bank_latitude,
                'longitude' => $request->bank_longitude,
                'partnership_level' => $request->bank_partnership_level,
                'admin_id' => $admin->id,
                'is_verified' => false, // Nécessite validation par un super admin
                'is_active' => true,
            ]);

            // Créer automatiquement les stocks pour tous les types de sang
            $bloodTypes = \App\Models\BloodType::all();
            foreach ($bloodTypes as $bloodType) {
                \App\Models\BloodStock::create([
                    'blood_bank_id' => $bloodBank->id,
                    'blood_type_id' => $bloodType->id,
                    'quantity_ml' => 0, // Stock initial à zéro
                    'minimum_threshold' => 1000, // Seuil d'alerte par défaut (1L)
                    'maximum_capacity' => 10000, // Capacité maximale par défaut (10L)
                    'last_updated' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Banque de sang et administrateur créés avec succès. Votre compte sera validé par un administrateur.',
                'data' => [
                    'blood_bank' => $bloodBank->load(['admin', 'bloodStocks.bloodType']),
                    'admin' => $admin,
                    'stocks_created' => $bloodTypes->count()
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Valide une banque de sang (pour les super admins)
     */
    public function verify($id)
    {
        $bloodBank = BloodBank::find($id);

        if (!$bloodBank) {
            return response()->json([
                'success' => false,
                'message' => 'Banque de sang non trouvée'
            ], 404);
        }

        $bloodBank->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Banque de sang validée avec succès',
            'blood_bank' => $bloodBank->load('admin')
        ]);
    }

    /**
     * Liste des banques en attente de validation
     */
    public function pendingVerification()
    {
        $pendingBanks = BloodBank::where('is_verified', false)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pendingBanks
        ]);
    }
}
