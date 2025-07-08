@extends('layouts.app')

@section('title', 'À propos - BloodLink')
@section('description', 'Découvrez BloodLink et notre mission pour sauver des vies.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">À propos de BloodLink</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                BloodLink est une plateforme innovante qui connecte donneurs, médecins et banques de sang
                pour faciliter les dons et sauver des vies.
            </p>
        </div>

        <!-- Mission Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Notre Mission</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        Nous croyons que chaque don de sang peut sauver jusqu'à trois vies.
                        Notre mission est de simplifier le processus de don de sang en connectant
                        tous les acteurs du système de santé.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700">Faciliter les dons de sang</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700">Connecter donneurs et banques</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="text-gray-700">Sauver des vies</span>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-red-50 rounded-lg p-8">
                        <div class="text-4xl font-bold text-red-600 mb-2">10k+</div>
                        <div class="text-lg text-gray-600">Vies sauvées</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Solidarité</h3>
                <p class="text-gray-600">
                    Nous croyons en la solidarité humaine et en l'importance de s'entraider
                    pour sauver des vies.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Innovation</h3>
                <p class="text-gray-600">
                    Nous utilisons la technologie pour moderniser et simplifier
                    le processus de don de sang.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Efficacité</h3>
                <p class="text-gray-600">
                    Nous optimisons chaque étape du processus pour garantir
                    une réponse rapide aux besoins.
                </p>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Rejoignez-nous</h2>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Ensemble, nous pouvons faire la différence et sauver plus de vies.
                Rejoignez notre communauté de donneurs dès aujourd'hui.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                    Devenir donneur
                </a>
                <a href="{{ route('contact') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                    Nous contacter
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
