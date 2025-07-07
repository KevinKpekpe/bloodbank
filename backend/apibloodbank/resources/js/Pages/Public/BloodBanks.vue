<template>
    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        Trouvez une banque de sang
                    </h1>
                    <p class="text-xl text-red-100 max-w-3xl mx-auto">
                        Localisez rapidement la banque de sang la plus proche de chez vous.
                        Géolocalisation précise et informations détaillées.
                    </p>
                </div>
            </div>
        </section>

        <!-- Search and Filters Section -->
        <section class="bg-white py-8 border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <div class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Rechercher par ville, code postal..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                @input="debounceSearch"
                            />
                            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Blood Type Filter -->
                    <div>
                        <select
                            v-model="selectedBloodType"
                            @change="fetchBanks"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        >
                            <option value="">Tous les types de sang</option>
                            <option value="1">A+</option>
                            <option value="2">A-</option>
                            <option value="3">B+</option>
                            <option value="4">B-</option>
                            <option value="5">AB+</option>
                            <option value="6">AB-</option>
                            <option value="7">O+</option>
                            <option value="8">O-</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-6">
                    <button
                        @click="getCurrentLocation"
                        class="btn-primary flex items-center justify-center"
                        :disabled="isLoadingLocation"
                    >
                        <svg v-if="isLoadingLocation" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ isLoadingLocation ? 'Localisation...' : 'Ma position' }}
                    </button>
                    <button
                        @click="showAllBanks"
                        class="btn-outline"
                    >
                        Voir toutes les banques
                    </button>
                </div>
            </div>
        </section>

        <!-- Map and Results Section -->
        <section class="bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Map Container -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <h2 class="text-xl font-semibold text-gray-900">Carte des banques de sang</h2>
                                <p class="text-gray-600">{{ filteredBanks.length }} banque(s) trouvée(s)</p>
                            </div>
                            <BloodBankMap
                                :banks="filteredBanks"
                                :user-location="userLocation"
                                @bank-selected="selectBank"
                            />
                        </div>
                    </div>

                    <!-- Results List -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-lg">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Banques de sang</h3>
                                <p class="text-sm text-gray-600">{{ filteredBanks.length }} résultat(s)</p>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Loading State -->
                                <div v-if="isLoading" class="p-6 text-center">
                                    <svg class="animate-spin w-8 h-8 mx-auto mb-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-gray-600">Recherche en cours...</p>
                                </div>

                                <!-- Empty State -->
                                <div v-else-if="filteredBanks.length === 0" class="p-6 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"/>
                                    </svg>
                                    <p>Aucune banque trouvée</p>
                                    <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                                </div>

                                <!-- Results -->
                                <div v-else>
                                    <div
                                        v-for="bank in filteredBanks"
                                        :key="bank.id"
                                        class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
                                        @click="selectBank(bank)"
                                    >
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ bank.name }}</h4>
                                                <p class="text-sm text-gray-600">{{ bank.address }}</p>
                                                <p class="text-sm text-gray-500">{{ bank.city }}, {{ bank.postal_code }}</p>
                                                <div class="flex items-center mt-2">
                                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm text-gray-600">{{ bank.distance }} km</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="flex items-center space-x-1">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Ouvert
                                                    </span>
                                                </div>
                                                <button
                                                    @click.stop="getDirections(bank)"
                                                    class="mt-2 text-sm text-red-600 hover:text-red-700 font-medium"
                                                >
                                                    Itinéraire
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bank Details Modal -->
        <div v-if="selectedBank" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ selectedBank.name }}</h3>
                        <button @click="selectedBank = null" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Adresse</h4>
                            <p class="text-gray-600">{{ selectedBank.address }}</p>
                            <p class="text-gray-600">{{ selectedBank.city }}, {{ selectedBank.postal_code }}</p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Contact</h4>
                            <p class="text-gray-600">{{ selectedBank.phone }}</p>
                            <p class="text-gray-600">{{ selectedBank.email }}</p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Horaires</h4>
                            <p class="text-gray-600">Lundi - Vendredi : 8h - 18h</p>
                            <p class="text-gray-600">Samedi : 9h - 16h</p>
                            <p class="text-gray-600">Dimanche : Fermé</p>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Stock disponible</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">A+</span>
                                    <span class="text-sm font-semibold text-green-600">Disponible</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">O+</span>
                                    <span class="text-sm font-semibold text-green-600">Disponible</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">B+</span>
                                    <span class="text-sm font-semibold text-yellow-600">Faible</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <span class="text-sm">AB+</span>
                                    <span class="text-sm font-semibold text-red-600">Urgent</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button @click="getDirections(selectedBank)" class="btn-primary flex-1">
                            Obtenir l'itinéraire
                        </button>
                        <button @click="selectedBank = null" class="btn-outline flex-1">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import BloodBankMap from '@/Components/BloodBankMap.vue'

// Reactive data
const searchQuery = ref('')
const selectedBloodType = ref('')
const isLoadingLocation = ref(false)
const isLoading = ref(false)
const selectedBank = ref(null)
const userLocation = ref(null)
const filteredBanks = ref([])
const searchTimeout = ref(null)

// Computed properties
const hasFilters = computed(() => {
    return searchQuery.value || selectedBloodType.value || userLocation.value
})

// Methods
const fetchBanks = async () => {
    isLoading.value = true
    try {
        const params = {
            blood_type_id: selectedBloodType.value || null,
            search: searchQuery.value || null
        }
        if (userLocation.value) {
            params.latitude = userLocation.value.lat
            params.longitude = userLocation.value.lng
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        const response = await fetch('/blood-banks/search/nearby', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(params)
        })
        const data = await response.json()
        if (data.success) {
            filteredBanks.value = data.data
        } else {
            filteredBanks.value = []
        }
    } catch (error) {
        filteredBanks.value = []
    } finally {
        isLoading.value = false
    }
}

const debounceSearch = () => {
    if (searchTimeout.value) {
        clearTimeout(searchTimeout.value)
    }

    searchTimeout.value = setTimeout(() => {
        fetchBanks()
    }, 500)
}

const getCurrentLocation = () => {
    isLoadingLocation.value = true

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLocation.value = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                }

                fetchBanks()
                isLoadingLocation.value = false
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error)
                isLoadingLocation.value = false
                alert('Impossible de récupérer votre position')
            }
        )
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur')
        isLoadingLocation.value = false
    }
}

const showAllBanks = () => {
    searchQuery.value = ''
    selectedBloodType.value = ''
    userLocation.value = null
    fetchBanks()
}

const selectBank = (bank) => {
    selectedBank.value = bank
}

const getDirections = (bank) => {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${bank.latitude},${bank.longitude}`
    window.open(url, '_blank')
}

// Lifecycle
onMounted(() => {
    fetchBanks()
})
</script>

<style scoped>
/* Custom styles for the map */
#map {
    z-index: 1;
}

/* Override Leaflet default styles */
:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
}

:deep(.leaflet-popup-content) {
    margin: 8px;
    font-family: 'Inter', sans-serif;
}
</style>
