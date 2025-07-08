<template>
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <router-link to="/" class="flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">BloodBank</span>
                        </router-link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <router-link
                            to="/"
                            class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            active-class="border-red-500 text-gray-900"
                        >
                            Accueil
                        </router-link>
                        <router-link
                            to="/blood-banks"
                            class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            active-class="border-red-500 text-gray-900"
                        >
                            Banques de sang
                        </router-link>
                        <router-link
                            to="/blood-bank/register"
                            class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            active-class="border-red-500 text-gray-900"
                        >
                            Rejoindre le réseau
                        </router-link>
                        <router-link
                            to="/about"
                            class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            active-class="border-red-500 text-gray-900"
                        >
                            À propos
                        </router-link>
                        <router-link
                            to="/contact"
                            class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                            active-class="border-red-500 text-gray-900"
                        >
                            Contact
                        </router-link>
                    </div>
                </div>

                <!-- Right side -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Notification Center -->
                    <NotificationCenter v-if="isAuthenticated" class="mr-4" />

                    <!-- User menu -->
                    <div v-if="isAuthenticated" class="ml-3 relative">
                        <div>
                            <button
                                @click="toggleUserMenu"
                                class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                <span class="sr-only">Ouvrir le menu utilisateur</span>
                                <div class="h-8 w-8 rounded-full bg-red-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ userInitials }}
                                    </span>
                                </div>
                            </button>
                        </div>

                        <!-- User dropdown menu -->
                        <div
                            v-if="showUserMenu"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                        >
                            <div class="px-4 py-2 text-sm text-gray-700 border-b">
                                <div class="font-medium">{{ currentUser?.name }}</div>
                                <div class="text-gray-500">{{ currentUser?.email }}</div>
                            </div>
                            <router-link
                                to="/dashboard"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="showUserMenu = false"
                            >
                                Dashboard
                            </router-link>
                            <router-link
                                v-if="currentUser?.role === 'blood_bank'"
                                to="/blood-bank/dashboard"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="showUserMenu = false"
                            >
                                Dashboard Banque
                            </router-link>
                            <router-link
                                to="/donations"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="showUserMenu = false"
                            >
                                Mes dons
                            </router-link>
                            <router-link
                                to="/notifications"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="showUserMenu = false"
                            >
                                Notifications
                            </router-link>
                            <router-link
                                to="/profile"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                @click="showUserMenu = false"
                            >
                                Mon profil
                            </router-link>
                            <button
                                @click="logout"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                Se déconnecter
                            </button>
                        </div>
                    </div>

                    <!-- Auth buttons -->
                    <div v-else class="flex items-center space-x-4">
                        <router-link
                            to="/login"
                            class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium"
                        >
                            Connexion
                        </router-link>
                        <router-link
                            to="/register"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Inscription
                        </router-link>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        @click="toggleMobileMenu"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-red-500"
                    >
                        <span class="sr-only">Ouvrir le menu principal</span>
                        <svg
                            class="h-6 w-6"
                            :class="{ 'hidden': showMobileMenu, 'block': !showMobileMenu }"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg
                            class="h-6 w-6"
                            :class="{ 'block': showMobileMenu, 'hidden': !showMobileMenu }"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="showMobileMenu" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <router-link
                    to="/"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                    active-class="bg-red-50 border-red-500 text-red-700"
                    @click="showMobileMenu = false"
                >
                    Accueil
                </router-link>
                <router-link
                    to="/blood-banks"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                    active-class="bg-red-50 border-red-500 text-red-700"
                    @click="showMobileMenu = false"
                >
                    Banques de sang
                </router-link>
                <router-link
                    to="/blood-bank/register"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                    active-class="bg-red-50 border-red-500 text-red-700"
                    @click="showMobileMenu = false"
                >
                    Rejoindre le réseau
                </router-link>
                <router-link
                    to="/about"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                    active-class="bg-red-50 border-red-500 text-red-700"
                    @click="showMobileMenu = false"
                >
                    À propos
                </router-link>
                <router-link
                    to="/contact"
                    class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                    active-class="bg-red-50 border-red-500 text-red-700"
                    @click="showMobileMenu = false"
                >
                    Contact
                </router-link>
            </div>

            <!-- Mobile auth menu -->
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div v-if="isAuthenticated" class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ userInitials }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ currentUser?.name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ currentUser?.email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <router-link
                        v-if="isAuthenticated"
                        to="/dashboard"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Dashboard
                    </router-link>
                    <router-link
                        v-if="isAuthenticated && currentUser?.role === 'blood_bank'"
                        to="/blood-bank/dashboard"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Dashboard Banque
                    </router-link>
                    <router-link
                        v-if="isAuthenticated"
                        to="/donations"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Mes dons
                    </router-link>
                    <router-link
                        v-if="isAuthenticated"
                        to="/notifications"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Notifications
                    </router-link>
                    <router-link
                        v-if="isAuthenticated"
                        to="/profile"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Mon profil
                    </router-link>
                    <button
                        v-if="isAuthenticated"
                        @click="logout"
                        class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                    >
                        Se déconnecter
                    </button>
                    <router-link
                        v-if="!isAuthenticated"
                        to="/login"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Connexion
                    </router-link>
                    <router-link
                        v-if="!isAuthenticated"
                        to="/register"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
                        @click="showMobileMenu = false"
                    >
                        Inscription
                    </router-link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import AuthService from '@/Services/AuthService'
import NotificationCenter from './NotificationCenter.vue'

const router = useRouter()

// State
const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const isAuthenticated = ref(false)
const currentUser = ref(null)

// Computed
const userInitials = computed(() => {
    if (!currentUser.value?.name) return '?'
    return currentUser.value.name
        .split(' ')
        .map(name => name.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2)
})

// Methods
const toggleUserMenu = () => {
    showUserMenu.value = !showUserMenu.value
}

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value
}

const logout = async () => {
    try {
        await AuthService.logout()
        isAuthenticated.value = false
        currentUser.value = null
        showUserMenu.value = false
        showMobileMenu.value = false
        router.push('/')
    } catch (error) {
        console.error('Erreur lors de la déconnexion:', error)
    }
}

const checkAuth = () => {
    isAuthenticated.value = AuthService.isAuthenticated()
    currentUser.value = AuthService.getCurrentUser()
}

// Close menus when clicking outside
const handleClickOutside = (event) => {
    if (!event.target.closest('.relative')) {
        showUserMenu.value = false
    }
}

// Lifecycle
onMounted(() => {
    checkAuth()
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})

// Watch for auth changes
const checkAuthInterval = setInterval(checkAuth, 1000)

onUnmounted(() => {
    clearInterval(checkAuthInterval)
})
</script>
