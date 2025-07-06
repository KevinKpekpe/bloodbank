<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentification
 *
 * APIs pour la gestion de l'authentification des utilisateurs
 */
class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur
     *
     * Permet à un utilisateur de s'inscrire sur la plateforme avec un rôle spécifique.
     *
     * @bodyParam name string required Le nom complet de l'utilisateur. Example: Jean Dupont
     * @bodyParam email string required L'adresse email unique. Example: jean.dupont@example.com
     * @bodyParam password string required Le mot de passe (minimum 8 caractères). Example: password123
     * @bodyParam password_confirmation string required La confirmation du mot de passe. Example: password123
     * @bodyParam phone string Le numéro de téléphone. Example: +33123456789
     * @bodyParam address string L'adresse postale. Example: 123 Rue de la Paix
     * @bodyParam city string La ville. Example: Paris
     * @bodyParam postal_code string Le code postal. Example: 75001
     * @bodyParam country string Le pays. Example: France
     * @bodyParam latitude number La latitude (pour la géolocalisation). Example: 48.8566
     * @bodyParam longitude number La longitude (pour la géolocalisation). Example: 2.3522
     * @bodyParam blood_type_id integer L'ID du groupe sanguin. Example: 1
     * @bodyParam role string required Le rôle de l'utilisateur (donor, doctor, blood_bank, admin). Example: donor
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Utilisateur créé avec succès",
     *   "user": {
     *     "id": 1,
     *     "name": "Jean Dupont",
     *     "email": "jean.dupont@example.com",
     *     "phone": "+33123456789",
     *     "role": {
     *       "id": 2,
     *       "name": "donor"
     *     },
     *     "blood_type": {
     *       "id": 1,
     *       "name": "A+"
     *     }
     *   }
     * }
     *
     * @response 422 {
     *   "message": "Erreur de validation",
     *   "errors": {
     *     "email": ["L'adresse email est déjà utilisée."]
     *   }
     * }
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'role' => 'required|in:donor,blood_bank,doctor',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Récupérer le rôle correspondant
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return response()->json([
                'message' => 'Rôle invalide'
            ], 400);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'blood_type_id' => $request->blood_type_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'role_id' => $role->id,
            'is_eligible_donor' => $request->role === 'donor',
        ]);

        // Créer le token d'authentification
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user->load('role', 'bloodType'),
            'token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Connexion utilisateur
     *
     * Authentifie un utilisateur et retourne un token d'accès.
     *
     * @bodyParam email string required L'adresse email. Example: jean.dupont@example.com
     * @bodyParam password string required Le mot de passe. Example: password123
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Connexion réussie",
     *   "user": {
     *     "id": 1,
     *     "name": "Jean Dupont",
     *     "email": "jean.dupont@example.com",
     *     "role": {
     *       "name": "donor"
     *     }
     *   },
     *   "token": "1|abc123def456..."
     * }
     *
     * @response 401 {
     *   "message": "Identifiants invalides"
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user->load('role', 'bloodType'),
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Déconnexion utilisateur
     *
     * Invalide le token d'accès de l'utilisateur connecté.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Déconnexion réussie"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Profil utilisateur connecté
     *
     * Récupère les informations du profil de l'utilisateur connecté.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "user": {
     *     "id": 1,
     *     "name": "Jean Dupont",
     *     "email": "jean.dupont@example.com",
     *     "phone": "+33123456789",
     *     "role": {
     *       "name": "donor"
     *     },
     *     "blood_type": {
     *       "name": "A+"
     *     }
     *   }
     * }
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('role', 'bloodType')
        ]);
    }

    /**
     * Rafraîchir le token
     *
     * Génère un nouveau token d'accès pour l'utilisateur connecté.
     *
     * @authenticated
     *
     * @response 200 {
     *   "success": true,
     *   "token": "2|xyz789abc123..."
     * }
     */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }
}
