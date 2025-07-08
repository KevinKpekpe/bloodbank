<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodBankRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DonationController;
use Illuminate\Support\Facades\Auth;

// Routes publiques
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/donate', [PublicController::class, 'donate'])->name('donate');

// Routes pour les banques de sang
Route::get('/blood-banks', [BloodBankController::class, 'publicIndex'])->name('blood-banks');
Route::post('/blood-banks/search/nearby', [BloodBankController::class, 'searchNearby'])->name('blood-banks.search');

// Routes d'enregistrement des banques de sang
Route::get('/blood-bank/register', [BloodBankRegistrationController::class, 'showRegistrationForm'])->name('blood-bank.register');
Route::post('/blood-bank/register', [BloodBankRegistrationController::class, 'register'])->name('blood-bank.register.submit');

// Routes d'authentification
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginWeb']);
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerWeb']);

// Routes pour le mot de passe (placeholder)
Route::get('/forgot-password', function() {
    return redirect()->route('login')->with('message', 'Fonctionnalité en cours de développement');
})->name('password.request');

// Route pour le contact
Route::post('/contact', [PublicController::class, 'sendContact'])->name('contact.send');

// Routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Calculer les statistiques de base
        $stats = [
            'donations' => \App\Models\Donation::where('donor_id', $user->id)->where('status', 'completed')->count(),
            'livesSaved' => \App\Models\Donation::where('donor_id', $user->id)->where('status', 'completed')->count() * 3, // Estimation
            'nextDonation' => \App\Models\Donation::where('donor_id', $user->id)->where('status', 'scheduled')->orderBy('donation_date', 'asc')->first() ?
                \Carbon\Carbon::parse(\App\Models\Donation::where('donor_id', $user->id)->where('status', 'scheduled')->orderBy('donation_date', 'asc')->first()->donation_date)->format('d/m/Y') :
                'Non planifié'
        ];

        return view('dashboard', compact('stats'));
    })->name('dashboard');

    // Routes pour les dons
    Route::get('/donations', [DonationController::class, 'showDonationsPage'])->name('donations.index');
    Route::get('/donations/book', [DonationController::class, 'showBookingForm'])->name('donations.book');
    Route::post('/donations/book', [DonationController::class, 'bookAppointmentWeb'])->name('donations.book.submit');
    Route::delete('/donations/{id}/cancel', [DonationController::class, 'cancelAppointmentWeb'])->name('donations.cancel');
});
