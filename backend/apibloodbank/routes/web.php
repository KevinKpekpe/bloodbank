<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use Inertia\Inertia;

// Routes publiques
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/donate', [PublicController::class, 'donate'])->name('donate');

// Routes pour les banques de sang (sera développé dans la phase suivante)
Route::get('/blood-banks', function () {
    return Inertia::render('Public/BloodBanks');
})->name('blood-banks');

// Routes d'authentification (sera développé dans la phase suivante)
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');
