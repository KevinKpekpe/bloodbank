<template>
    <AppLayout>
        <div class="min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Page non trouvée</h2>
                    <p class="text-gray-600 mb-8">
                        Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
                    </p>
                </div>

                <div class="space-y-4">
                    <router-link
                        to="/"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Retour à l'accueil
                    </router-link>

                    <div class="text-sm text-gray-500">
                        Ou essayez de naviguer vers une de ces pages :
                    </div>

                    <div class="flex flex-wrap justify-center gap-4">
                        <router-link
                            to="/blood-banks"
                            class="text-red-600 hover:text-red-700 font-medium"
                        >
                            Banques de sang
                        </router-link>
                        <router-link
                            to="/about"
                            class="text-red-600 hover:text-red-700 font-medium"
                        >
                            À propos
                        </router-link>
                        <router-link
                            to="/contact"
                            class="text-red-600 hover:text-red-700 font-medium"
                        >
                            Contact
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