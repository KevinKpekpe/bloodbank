<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Affiche la page d'accueil
     */
    public function home()
    {
        return view('home');
    }

    /**
     * Affiche la page "À propos"
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Affiche la page de contact
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Affiche la page de don
     */
    public function donate()
    {
        return view('donate');
    }

    /**
     * Traite l'envoi du formulaire de contact
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Pour l'instant, on simule l'envoi
        // TODO: Implémenter l'envoi réel d'email

        return redirect()->route('contact')->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
    }
}
