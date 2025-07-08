@extends('layouts.app')

@section('title', 'Devenir Donneur - BloodLink')
@section('description', 'Découvrez comment devenir donneur de sang et sauver des vies.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Devenir donneur</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Votre don peut sauver jusqu'à trois vies. Découvrez comment devenir donneur
                et rejoindre notre communauté de héros du quotidien.
            </p>
        </div>

        <!-- Why Donate Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Pourquoi donner son sang ?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Sauver des vies</h3>
                    <p class="text-gray-600">
                        Un seul don peut sauver jusqu'à trois vies.
                        Votre sang est essentiel pour les urgences et les traitements.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">C'est simple</h3>
                    <p class="text-gray-600">
                        Le don de sang ne prend que 10 minutes et est sans danger.
                        Notre équipe vous accompagne à chaque étape.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Urgent</h3>
                    <p class="text-gray-600">
                        Les besoins en sang sont constants.
                        Chaque jour, des vies dépendent de la générosité des donneurs.
                    </p>
                </div>
            </div>
        </div>

        <!-- Eligibility Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Requirements -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Critères d'éligibilité</h3>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Âge entre 18 et 70 ans</p>
                            <p class="text-sm text-gray-600">Pour un premier don, vous devez avoir entre 18 et 65 ans</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Poids minimum de 50kg</p>
                            <p class="text-sm text-gray-600">Pour assurer votre sécurité pendant le don</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Être en bonne santé</p>
                            <p class="text-sm text-gray-600">Pas de fièvre, infection ou traitement en cours</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Pièce d'identité</p>
                            <p class="text-sm text-gray-600">Carte d'identité, passeport ou permis de conduire</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Process -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Comment ça marche ?</h3>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center mr-4 font-bold">
                            1
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Inscription</h4>
                            <p class="text-gray-600">Créez votre compte sur BloodLink et complétez votre profil</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center mr-4 font-bold">
                            2
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Prise de rendez-vous</h4>
                            <p class="text-gray-600">Choisissez une banque de sang et réservez votre créneau</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center mr-4 font-bold">
                            3
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Entretien médical</h4>
                            <p class="text-gray-600">Un médecin vérifie votre éligibilité et répond à vos questions</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center mr-4 font-bold">
                            4
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Don de sang</h4>
                            <p class="text-gray-600">Le prélèvement dure environ 10 minutes</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center mr-4 font-bold">
                            5
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Collation</h4>
                            <p class="text-gray-600">Reposez-vous et prenez une collation offerte</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preparation Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Préparation au don</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Avant le don</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Dormez bien la veille</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Mangez un repas léger</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Buvez beaucoup d'eau</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Apportez une pièce d'identité</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Après le don</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Reposez-vous 15 minutes</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Buvez beaucoup d'eau</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Évitez l'effort physique intense</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Attendez 8 semaines pour le prochain don</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <h3 class="text-3xl font-bold text-gray-900 mb-4">Prêt à sauver des vies ?</h3>
            <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                Rejoignez notre communauté de donneurs et faites la différence dès aujourd'hui.
                Votre don peut sauver des vies.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                    Devenir donneur
                </a>
                <a href="{{ route('blood-banks') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                    Trouver une banque
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
