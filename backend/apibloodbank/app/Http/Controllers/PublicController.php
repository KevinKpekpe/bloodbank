<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function home()
    {
        return Inertia::render('Public/Home');
    }

    /**
     * Affiche la page "À propos"
     */
    public function about()
    {
        return Inertia::render('Public/About');
    }

    /**
     * Affiche la page de contact
     */
    public function contact()
    {
        return Inertia::render('Public/Contact');
    }

    /**
     * Affiche la page de don
     */
    public function donate()
    {
        return Inertia::render('Public/Donate');
    }
}
