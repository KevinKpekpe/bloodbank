<template>
    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Faire un don de sang</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Votre don peut sauver jusqu'à 3 vies. Découvrez comment vous pouvez contribuer à sauver des vies.
                    </p>
                </div>

                <!-- Why Donate -->
                <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg shadow-lg p-8 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div>
                            <h2 class="text-3xl font-bold mb-4">Pourquoi donner son sang ?</h2>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-red-200 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Un don peut sauver jusqu'à 3 vies</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-red-200 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Le sang ne peut pas être fabriqué artificiellement</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-red-200 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Un besoin constant dans les hôpitaux</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-6 h-6 text-red-200 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Processus simple et sécurisé</span>
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-2xl font-bold">Chaque don compte</p>
                        </div>
                    </div>
                </div>

                <!-- Eligibility -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Critères d'éligibilité</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Âge</h3>
                                    <p class="text-gray-600">Entre 18 et 70 ans</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Poids</h3>
                                    <p class="text-gray-600">Plus de 50 kg</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Santé</h3>
                                    <p class="text-gray-600">Être en bonne santé générale</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Fréquence</h3>
                                    <p class="text-gray-600">Maximum 4 dons par an pour les hommes, 3 pour les femmes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Contre-indications</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Maladies infectieuses</h3>
                                    <p class="text-gray-600">VIH, hépatites B et C, etc.</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Grossesse</h3>
                                    <p class="text-gray-600">Pendant la grossesse et 6 mois après l'accouchement</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Voyages récents</h3>
                                    <p class="text-gray-600">Certaines destinations peuvent nécessiter un délai</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Tatouages/Piercings</h3>
                                    <p class="text-gray-600">Délai de 4 mois après un tatouage ou piercing</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Process -->
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Le processus de don</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                                1
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Inscription</h3>
                            <p class="text-gray-600">Créez votre compte et renseignez vos informations</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                                2
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Rendez-vous</h3>
                            <p class="text-gray-600">Prenez rendez-vous dans une banque de sang</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                                3
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Don</h3>
                            <p class="text-gray-600">Le don dure environ 10 minutes</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-xl font-bold">
                                4
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Collation</h3>
                            <p class="text-gray-600">Reposez-vous et prenez une collation</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Prêt à sauver des vies ?</h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Rejoignez notre communauté de donneurs et faites la différence dès aujourd'hui.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <router-link to="/register" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                            Devenir donneur
                        </router-link>
                        <router-link to="/blood-banks" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                            Trouver une banque
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>
