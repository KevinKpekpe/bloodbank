<template>
    <PublicLayout>
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
                            BloodLink connecte donneurs, médecins et banques de sang pour faciliter les dons et sauver des vies.
                            Votre don peut faire la différence.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <Link href="/blood-banks" class="btn-secondary text-lg px-8 py-4">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Trouver une banque
                            </Link>
                            <Link href="/register" class="btn-primary text-lg px-8 py-4">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Devenir donneur
                            </Link>
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
                        Pourquoi choisir BloodLink ?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Une plateforme complète qui simplifie le processus de don de sang
                        et connecte tous les acteurs du système de santé.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="card p-8 text-center">
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
                    <div class="card p-8 text-center">
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
                    <div class="card p-8 text-center">
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

                    <!-- Feature 4 -->
                    <div class="card p-8 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Urgences prioritaires</h3>
                        <p class="text-gray-600">
                            Système d'alerte pour les demandes urgentes de sang.
                            Réponse immédiate en cas d'urgence.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="card p-8 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Statistiques détaillées</h3>
                        <p class="text-gray-600">
                            Visualisez l'impact de votre contribution avec des statistiques
                            détaillées et des rapports personnalisés.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="card p-8 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Communauté solidaire</h3>
                        <p class="text-gray-600">
                            Rejoignez une communauté de donneurs engagés et partagez
                            votre expérience pour encourager d'autres.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works Section -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Comment ça marche ?
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Le processus de don de sang simplifié en 4 étapes simples
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="relative">
                            <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                1
                            </div>
                            <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-red-200 transform translate-x-4"></div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Inscription</h3>
                        <p class="text-gray-600">
                            Créez votre compte en quelques minutes et renseignez vos informations médicales
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="relative">
                            <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                2
                            </div>
                            <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-red-200 transform translate-x-4"></div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Recherche</h3>
                        <p class="text-gray-600">
                            Trouvez la banque de sang la plus proche et vérifiez les disponibilités
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="relative">
                            <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                                3
                            </div>
                            <div class="hidden lg:block absolute top-10 left-full w-full h-0.5 bg-red-200 transform translate-x-4"></div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Rendez-vous</h3>
                        <p class="text-gray-600">
                            Prenez rendez-vous en ligne et recevez une confirmation par email
                        </p>
                    </div>

                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                            4
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Don</h3>
                        <p class="text-gray-600">
                            Rendez-vous à la banque de sang et faites votre don en toute sécurité
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-red-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">
                    Prêt à sauver des vies ?
                </h2>
                <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
                    Rejoignez notre communauté de donneurs et faites la différence.
                    Votre don peut sauver jusqu'à 3 vies.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link href="/register" class="btn-secondary text-lg px-8 py-4">
                        Commencer maintenant
                    </Link>
                    <Link href="/blood-banks" class="btn-outline text-lg px-8 py-4 border-white text-white hover:bg-white hover:text-red-600">
                        Voir les banques
                    </Link>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Ils témoignent
                    </h2>
                    <p class="text-xl text-gray-600">
                        Découvrez les histoires de ceux qui ont fait la différence
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="card p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-red-600 font-semibold">M</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Marie Dubois</h4>
                                <p class="text-gray-600">Donneuse régulière</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "BloodLink a simplifié mon processus de don. Je peux maintenant prendre rendez-vous
                            en quelques clics et suivre l'impact de mes dons. C'est motivant !"
                        </p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="card p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-red-600 font-semibold">D</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Dr. Thomas Martin</h4>
                                <p class="text-gray-600">Médecin urgentiste</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "En tant que médecin, je vois l'impact direct des dons de sang. BloodLink nous permet
                            de trouver rapidement le sang dont nous avons besoin en urgence."
                        </p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="card p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-red-600 font-semibold">S</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Sophie Laurent</h4>
                                <p class="text-gray-600">Banque de sang</p>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">
                            "BloodLink nous aide à mieux gérer nos stocks et à communiquer avec les donneurs.
                            C'est un outil indispensable pour notre banque de sang."
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>

<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { Link } from '@inertiajs/vue3'
</script>
