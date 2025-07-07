<template>
    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">À propos de BloodBank</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Une plateforme innovante qui connecte donneurs, banques de sang et médecins pour sauver des vies.
                    </p>
                </div>

                <!-- Mission -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Notre mission</h2>
                    <p class="text-gray-600 mb-4">
                        BloodBank a été créé avec une mission simple mais vitale : faciliter les dons de sang et sauver des vies.
                        Nous croyons que chaque don compte et que la technologie peut jouer un rôle crucial dans la mobilisation
                        des donneurs et l'optimisation des ressources.
                    </p>
                    <p class="text-gray-600">
                        Notre plateforme connecte tous les acteurs du système de don de sang pour créer un écosystème
                        efficace et accessible à tous.
                    </p>
                </div>

                <!-- Values -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Solidarité</h3>
                        <p class="text-gray-600">
                            Nous croyons en la solidarité humaine et en la capacité de chacun à contribuer au bien commun.
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
                            Nous utilisons les dernières technologies pour améliorer l'expérience des donneurs et optimiser les processus.
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
                            Nous nous efforçons de rendre le processus de don de sang plus rapide, plus simple et plus efficace.
                        </p>
                    </div>
                </div>

                <!-- Team -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Notre équipe</h2>
                    <p class="text-gray-600 mb-6">
                        BloodBank est développé par une équipe passionnée de développeurs, designers et professionnels de santé
                        qui partagent la même vision : utiliser la technologie pour sauver des vies.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Équipe de développement</h3>
                            <p class="text-gray-600">Experts en technologies web et mobile</p>
                        </div>

                        <div class="text-center">
                            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Conseillers médicaux</h3>
                            <p class="text-gray-600">Professionnels de santé expérimentés</p>
                        </div>

                        <div class="text-center">
                            <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Support utilisateur</h3>
                            <p class="text-gray-600">Équipe dédiée à l'assistance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
