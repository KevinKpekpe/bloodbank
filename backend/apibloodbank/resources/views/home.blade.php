@extends('layouts.app')

@section('title', 'BloodLink - Sauvez des vies, donnez votre sang')
@section('description', 'Plateforme de dons de sang connectant donneurs, médecins et banques de sang pour sauver des vies.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-red-600 to-red-700 text-white overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 hero-pattern opacity-10"></div>

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse-red"></div>
    <div class="absolute top-40 right-20 w-16 h-16 bg-white/10 rounded-full animate-pulse-red" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white/10 rounded-full animate-pulse-red" style="animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                    Sauvez des vies,
                    <span class="block text-red-200">donnez votre sang</span>
                </h1>
                <p class="text-xl md:text-2xl text-red-100 mb-8 leading-relaxed">
                    BloodBank connecte donneurs, médecins et banques de sang pour faciliter les dons et sauver des vies.
                    Votre don peut faire la différence.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('blood-banks') }}" class="border-2 border-white text-white hover:bg-white hover:text-red-600 font-semibold rounded-lg transition-colors inline-flex items-center text-lg px-8 py-4">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Trouver une banque
                    </a>
                    <a href="{{ route('register') }}" class="bg-white text-red-600 hover:bg-red-50 font-semibold rounded-lg transition-colors inline-flex items-center text-lg px-8 py-4">
                        <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Devenir donneur
                    </a>
                </div>
            </div>

            <!-- Hero Image/Illustration -->
            <div class="relative">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Impact immédiat</h3>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <div class="text-3xl font-bold text-red-200">150+</div>
                                <div class="text-sm text-red-100">Banques partenaires</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-red-200">10k+</div>
                                <div class="text-sm text-red-100">Vies sauvées</div>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-red-200">24h</div>
                                <div class="text-sm text-red-100">Réponse rapide</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Pourquoi choisir BloodBank ?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Une plateforme complète qui simplifie le processus de don de sang
                et connecte tous les acteurs du système de santé.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Géolocalisation précise</h3>
                <p class="text-gray-600">
                    Trouvez rapidement la banque de sang la plus proche de chez vous
                    avec notre système de géolocalisation avancé.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Rendez-vous en ligne</h3>
                <p class="text-gray-600">
                    Prenez rendez-vous pour votre don de sang en quelques clics.
                    Plus besoin d'appeler ou de se déplacer.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Suivi en temps réel</h3>
                <p class="text-gray-600">
                    Suivez l'impact de votre don et recevez des notifications
                    sur l'utilisation de votre sang.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Prêt à sauver des vies ?
        </h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Rejoignez notre communauté de donneurs et faites la différence dès aujourd'hui.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                Commencer maintenant
            </a>
            <a href="{{ route('about') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                En savoir plus
            </a>
        </div>
    </div>
</section>

<style>
.hero-pattern {
    background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 20px 20px;
}

.animate-pulse-red {
    animation: pulse-red 3s infinite;
}

@keyframes pulse-red {
    0%, 100% {
        opacity: 0.1;
        transform: scale(1);
    }
    50% {
        opacity: 0.3;
        transform: scale(1.1);
    }
}
</style>
@endsection
