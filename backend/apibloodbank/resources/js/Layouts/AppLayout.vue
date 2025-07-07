<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <Navigation />

        <!-- Main content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Logo et description -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">BloodBank</span>
                        </div>
                        <p class="mt-4 text-gray-600 max-w-md">
                            Plateforme de gestion des dons de sang connectant donneurs, banques de sang et médecins pour sauver des vies.
                        </p>
                    </div>

                    <!-- Liens rapides -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Liens rapides</h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <router-link to="/blood-banks" class="text-base text-gray-500 hover:text-gray-900">
                                    Banques de sang
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/about" class="text-base text-gray-500 hover:text-gray-900">
                                    À propos
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/contact" class="text-base text-gray-500 hover:text-gray-900">
                                    Contact
                                </router-link>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Support</h3>
                        <ul class="mt-4 space-y-4">
                            <li>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                                    Centre d'aide
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                                    Conditions d'utilisation
                                </a>
                            </li>
                            <li>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                                    Politique de confidentialité
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <p class="text-base text-gray-400 text-center">
                        © 2024 BloodBank. Tous droits réservés.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import Navigation from '@/Components/Navigation.vue'
</script>
