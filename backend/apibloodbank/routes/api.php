<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BloodTypeController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\GeolocationController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route de test pour vérifier la connexion front-back
Route::get('/ping', function () {
    return response()->json([
        'message' => 'API BloodBank connectée avec succès !',
        'timestamp' => now()->toISOString(),
        'status' => 'success'
    ]);
});

// Routes publiques
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes pour les types de sang (publiques)
Route::prefix('blood-types')->group(function () {
    Route::get('/', [BloodTypeController::class, 'index']);
    Route::get('/{id}', [BloodTypeController::class, 'show']);
    Route::get('/{id}/compatible', [BloodTypeController::class, 'getCompatibleTypes']);
});

// Routes pour les banques de sang (publiques)
Route::prefix('blood-banks')->group(function () {
    Route::get('/', [BloodBankController::class, 'index']);
    Route::get('/{id}', [BloodBankController::class, 'show']);
    Route::get('/{id}/stock', [BloodBankController::class, 'getStock']);
    Route::post('/search/nearby', [BloodBankController::class, 'searchNearby']);
});

// Routes pour la géolocalisation
Route::prefix('geolocation')->group(function () {
    Route::post('/geocode', [GeolocationController::class, 'geocode']); // Publique
    Route::get('/statistics', [GeolocationController::class, 'statistics']); // Publique
    Route::post('/search-by-city', [GeolocationController::class, 'searchBanksByCity']); // Publique

    // Routes protégées
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/nearby-banks', [GeolocationController::class, 'nearbyBanks']);
        Route::post('/nearby-donors', [GeolocationController::class, 'nearbyDonors']);
        Route::post('/update-bank-coordinates', [GeolocationController::class, 'updateBankCoordinates'])->middleware('role:admin');
    });
});

// Routes pour les contacts
Route::post('/contact/send', [ContactController::class, 'send']); // Publique

// Routes protégées pour l'administration des contacts
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::patch('/contacts/{id}/process', [ContactController::class, 'markAsProcessed']);
    Route::patch('/contacts/{id}/progress', [ContactController::class, 'markAsInProgress']);
    Route::post('/contacts/{id}/respond', [ContactController::class, 'respond']);
    Route::get('/contacts/statistics', [ContactController::class, 'statistics']);
});

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {

    // Routes d'authentification
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // Routes pour les types de sang (Admin seulement)
    Route::prefix('blood-types')->middleware('role:admin')->group(function () {
        Route::post('/', [BloodTypeController::class, 'store']);
        Route::put('/{id}', [BloodTypeController::class, 'update']);
        Route::delete('/{id}', [BloodTypeController::class, 'destroy']);
    });

    // Routes pour les banques de sang (Admin et Blood Bank)
    Route::prefix('blood-banks')->group(function () {
        Route::post('/', [BloodBankController::class, 'store'])->middleware('role:admin');
        Route::put('/{id}', [BloodBankController::class, 'update'])->middleware('role:admin,blood_bank');
        Route::delete('/{id}', [BloodBankController::class, 'destroy'])->middleware('role:admin');
        Route::post('/{id}/verify', [BloodBankController::class, 'verify'])->middleware('role:admin');
        Route::get('/{id}/dashboard', [BloodBankController::class, 'dashboard']);
    });

    // Routes pour les dons (Donor, Blood Bank, Admin)
    Route::prefix('donations')->group(function () {
        Route::get('/', [DonationController::class, 'index']);
        Route::get('/{id}', [DonationController::class, 'show']);
        Route::post('/', [DonationController::class, 'store']);
        Route::put('/{id}', [DonationController::class, 'update']);
        Route::delete('/{id}', [DonationController::class, 'destroy']);
        Route::post('/{id}/complete', [DonationController::class, 'complete']);
        Route::post('/{id}/cancel', [DonationController::class, 'cancel']);
        Route::get('/donor/{donorId}/history', [DonationController::class, 'donorHistory']);
        Route::get('/statistics', [DonationController::class, 'statistics']);
    });

    // Routes pour la gestion du stock (Blood Bank, Admin)
    Route::prefix('stocks')->middleware('role:blood_bank,admin')->group(function () {
        Route::get('/', [StockController::class, 'index']);
        Route::get('/{id}', [StockController::class, 'show']);
        Route::post('/', [StockController::class, 'store']);
        Route::put('/{id}', [StockController::class, 'update']);
        Route::delete('/{id}', [StockController::class, 'destroy']);
        Route::post('/{id}/adjust', [StockController::class, 'adjust']);
        Route::get('/movements', [StockController::class, 'movements']);
        Route::get('/alerts/low-stock', [StockController::class, 'lowStockAlerts']);
        Route::get('/statistics', [StockController::class, 'statistics']);
    });

    // Routes pour les patients (Doctor, Admin)
    Route::prefix('patients')->middleware('role:doctor,admin')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::get('/{id}', [PatientController::class, 'show']);
        Route::post('/', [PatientController::class, 'store']);
        Route::put('/{id}', [PatientController::class, 'update']);
        Route::delete('/{id}', [PatientController::class, 'destroy']);
        Route::get('/statistics', [PatientController::class, 'statistics']);
        Route::post('/search', [PatientController::class, 'search']);
    });

    // Routes pour les demandes de sang (Doctor, Admin)
    Route::prefix('blood-requests')->middleware('role:doctor,admin')->group(function () {
        Route::get('/', [BloodRequestController::class, 'index']);
        Route::get('/{id}', [BloodRequestController::class, 'show']);
        Route::post('/', [BloodRequestController::class, 'store']);
        Route::put('/{id}', [BloodRequestController::class, 'update']);
        Route::delete('/{id}', [BloodRequestController::class, 'destroy']);
        Route::get('/{id}/availability', [BloodRequestController::class, 'searchAvailability']);
        Route::post('/{id}/approve', [BloodRequestController::class, 'approve']);
        Route::post('/{id}/cancel', [BloodRequestController::class, 'cancel']);
        Route::post('/{id}/fulfill', [BloodRequestController::class, 'fulfill']);
        Route::get('/statistics', [BloodRequestController::class, 'statistics']);
    });

    // Routes pour les notifications (protégées)
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    // Routes pour les urgences (protégées)
    Route::post('/emergencies/declare', [EmergencyController::class, 'declare']);
    Route::post('/emergencies/find-banks', [EmergencyController::class, 'findAvailableBanks']);
    Route::get('/emergencies/active', [EmergencyController::class, 'activeEmergencies']);
    Route::patch('/emergencies/{id}/status', [EmergencyController::class, 'updateStatus']);

    // Routes pour les partenariats (protégées)
    Route::get('/partnerships', [PartnershipController::class, 'index']);
    Route::post('/partnerships/request', [PartnershipController::class, 'requestPartnership']);
    Route::patch('/partnerships/{id}/respond', [PartnershipController::class, 'respondToPartnership']);
    Route::patch('/partnerships/{id}/terminate', [PartnershipController::class, 'terminatePartnership']);
    Route::get('/partnerships/statistics', [PartnershipController::class, 'statistics']);

    // Route de test pour l'utilisateur connecté
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user()->load('role', 'bloodType')
        ]);
    });
});
