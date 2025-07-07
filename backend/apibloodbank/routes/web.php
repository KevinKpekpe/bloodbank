<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\BloodBankController;
use App\Http\Controllers\BloodBankRegistrationController;
use Inertia\Inertia;

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

// Routes d'authentification (sera développé dans la phase suivante)
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');
