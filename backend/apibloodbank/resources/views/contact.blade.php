@extends('layouts.app')

@section('title', 'Contact - BloodLink')
@section('description', 'Contactez l\'équipe BloodLink pour toute question ou assistance.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Nous sommes là pour vous aider. N'hésitez pas à nous contacter pour toute question
                concernant les dons de sang ou notre plateforme.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"
                                   value="{{ old('name') }}">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                        <select name="subject" id="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                            <option value="">Sélectionner un sujet</option>
                            <option value="don" @if(old('subject') == 'don') selected @endif>Question sur les dons</option>
                            <option value="inscription" @if(old('subject') == 'inscription') selected @endif>Problème d'inscription</option>
                            <option value="technique" @if(old('subject') == 'technique') selected @endif>Problème technique</option>
                            <option value="partenariat" @if(old('subject') == 'partenariat') selected @endif>Partenariat</option>
                            <option value="autre" @if(old('subject') == 'autre') selected @endif>Autre</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" id="message" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors"
                                  placeholder="Décrivez votre question ou votre demande...">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        Envoyer le message
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-6">
                <!-- Contact Info Card -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Informations de contact</h3>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Email</p>
                                <p class="text-gray-600">contact@bloodlink.fr</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Téléphone</p>
                                <p class="text-gray-600">+33 1 23 45 67 89</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Adresse</p>
                                <p class="text-gray-600">123 Rue de la Santé<br>75001 Paris, France</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Card -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Questions fréquentes</h3>

                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Comment devenir donneur ?</h4>
                            <p class="text-sm text-gray-600">
                                Créez un compte sur notre plateforme et suivez les étapes d'inscription.
                                Nous vous guiderons tout au long du processus.
                            </p>
                        </div>

                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Quels sont les critères d'éligibilité ?</h4>
                            <p class="text-sm text-gray-600">
                                Vous devez avoir entre 18 et 70 ans, peser plus de 50kg et être en bonne santé.
                                Consultez notre guide complet pour plus de détails.
                            </p>
                        </div>

                        <div class="border-b border-gray-200 pb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Comment annuler un rendez-vous ?</h4>
                            <p class="text-sm text-gray-600">
                                Connectez-vous à votre compte et allez dans la section "Mes dons"
                                pour annuler ou modifier vos rendez-vous.
                            </p>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Problème technique ?</h4>
                            <p class="text-sm text-gray-600">
                                Si vous rencontrez des difficultés avec notre plateforme,
                                contactez notre équipe technique via ce formulaire.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
