<template>
    <AppLayout>
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
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center justify-center transition-colors"
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
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-semibold transition-colors"
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
                            <div id="map" class="h-96 bg-gray-100"></div>
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
                                                    <span class="text-sm text-gray-600">{{ bank.distance || 'N/A' }} km</span>
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
                            <p class="text-gray-600">Lundi - Vendredi: 8h - 18h</p>
                            <p class="text-gray-600">Samedi: 9h - 16h</p>
                            <p class="text-gray-600">Dimanche: Fermé</p>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button
                                @click="getDirections(selectedBank)"
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-semibold transition-colors"
                            >
                                Obtenir l'itinéraire
                            </button>
                            <button
                                @click="callBank(selectedBank)"
                                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 py-2 px-4 rounded-lg font-semibold transition-colors"
                            >
                                Appeler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// State
const searchQuery = ref('')
const selectedBloodType = ref('')
const isLoading = ref(false)
const isLoadingLocation = ref(false)
const userLocation = ref(null)
const banks = ref([])
const filteredBanks = ref([])
const selectedBank = ref(null)
const map = ref(null)
const markers = ref([])

// Methods
const initMap = () => {
    // Vérifier si Leaflet est disponible
    if (typeof L === 'undefined') {
        console.error('Leaflet n\'est pas chargé')
        return
    }

    // Détruire la carte existante si elle existe
    if (map.value) {
        map.value.remove()
    }

    // Initialiser la carte
    map.value = L.map('map').setView([46.603354, 1.888334], 6) // Centre de la France

    // Ajouter le tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map.value)

    // Ajouter les marqueurs pour les banques existantes
    updateMapMarkers()
}

const updateMapMarkers = () => {
    if (!map.value) return

    // Supprimer les marqueurs existants
    markers.value.forEach(marker => {
        map.value.removeLayer(marker)
    })
    markers.value = []

    // Ajouter les nouveaux marqueurs
    filteredBanks.value.forEach(bank => {
        if (bank.latitude && bank.longitude) {
            const marker = L.marker([bank.latitude, bank.longitude])
                .addTo(map.value)
                .bindPopup(`
                    <div class="p-2">
                        <h3 class="font-semibold text-lg">${bank.name}</h3>
                        <p class="text-sm text-gray-600">${bank.address}</p>
                        <p class="text-sm text-gray-500">${bank.city}, ${bank.postal_code}</p>
                        <button onclick="selectBankFromMap(${bank.id})" class="mt-2 bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Voir détails
                        </button>
                    </div>
                `)

            markers.value.push(marker)
        }
    })

    // Ajuster la vue si on a des marqueurs
    if (markers.value.length > 0) {
        const group = new L.featureGroup(markers.value)
        map.value.fitBounds(group.getBounds().pad(0.1))
    }
}

const selectBankFromMap = (bankId) => {
    const bank = filteredBanks.value.find(b => b.id === bankId)
    if (bank) {
        selectBank(bank)
    }
}

// Exposer la fonction globalement pour les popups
window.selectBankFromMap = selectBankFromMap

const debounceSearch = () => {
    // Implémentation du debounce pour la recherche
    setTimeout(() => {
        fetchBanks()
    }, 300)
}

const fetchBanks = async () => {
    isLoading.value = true
    console.log('Début de la recherche avec:', {
        search: searchQuery.value,
        blood_type_id: selectedBloodType.value,
        latitude: userLocation.value?.latitude,
        longitude: userLocation.value?.longitude
    })

    try {
        // Appel à l'API pour récupérer les banques
        const response = await fetch('/api/blood-banks/search/nearby', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                search: searchQuery.value,
                blood_type_id: selectedBloodType.value || null,
                latitude: userLocation.value?.latitude || null,
                longitude: userLocation.value?.longitude || null
            })
        })

        console.log('Réponse API:', response.status, response.statusText)

        if (response.ok) {
            const data = await response.json()
            console.log('Données reçues:', data)
            banks.value = data.data || []
            filteredBanks.value = banks.value
            console.log('Banques mises à jour:', banks.value.length)

            // Mettre à jour la carte avec les nouvelles données
            updateMapMarkers()
        } else {
            console.error('Erreur API:', response.status, response.statusText)
            const errorText = await response.text()
            console.error('Détails erreur:', errorText)
            // Fallback avec des données de test
            banks.value = [
                {
                    id: 1,
                    name: 'Centre de transfusion sanguine de Paris',
                    address: '123 Rue de la Paix',
                    city: 'Paris',
                    postal_code: '75001',
                    phone: '01 23 45 67 89',
                    email: 'contact@ctsparis.fr',
                    distance: 2.5
                },
                {
                    id: 2,
                    name: 'Banque de sang de Lyon',
                    address: '456 Avenue des Sciences',
                    city: 'Lyon',
                    postal_code: '69001',
                    phone: '04 78 12 34 56',
                    email: 'contact@bslyon.fr',
                    distance: 5.2
                }
            ]
            filteredBanks.value = banks.value
        }
    } catch (error) {
        console.error('Erreur lors de la récupération des banques:', error)
        // Fallback avec des données de test
        banks.value = [
            {
                id: 1,
                name: 'Centre de transfusion sanguine de Paris',
                address: '123 Rue de la Paix',
                city: 'Paris',
                postal_code: '75001',
                phone: '01 23 45 67 89',
                email: 'contact@ctsparis.fr',
                distance: 2.5
            },
            {
                id: 2,
                name: 'Banque de sang de Lyon',
                address: '456 Avenue des Sciences',
                city: 'Lyon',
                postal_code: '69001',
                phone: '04 78 12 34 56',
                email: 'contact@bslyon.fr',
                distance: 5.2
            }
        ]
        filteredBanks.value = banks.value
    } finally {
        isLoading.value = false
    }
}

const getCurrentLocation = () => {
    isLoadingLocation.value = true

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                userLocation.value = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                }

                // Ajouter un marqueur pour la position de l'utilisateur
                if (map.value) {
                    // Supprimer l'ancien marqueur de position utilisateur
                    if (window.userLocationMarker) {
                        map.value.removeLayer(window.userLocationMarker)
                    }

                    // Ajouter le nouveau marqueur
                    window.userLocationMarker = L.marker([userLocation.value.latitude, userLocation.value.longitude], {
                        icon: L.divIcon({
                            className: 'user-location-marker',
                            html: '<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                            iconSize: [20, 20],
                            iconAnchor: [10, 10]
                        })
                    }).addTo(map.value)
                    .bindPopup('Votre position')

                    // Centrer la carte sur la position de l'utilisateur
                    map.value.setView([userLocation.value.latitude, userLocation.value.longitude], 12)
                }

                fetchBanks()
                isLoadingLocation.value = false
            },
            (error) => {
                console.error('Erreur de géolocalisation:', error)
                isLoadingLocation.value = false
            }
        )
    } else {
        console.error('Géolocalisation non supportée')
        isLoadingLocation.value = false
    }
}

const showAllBanks = () => {
    userLocation.value = null
    fetchBanks()
}

const selectBank = (bank) => {
    selectedBank.value = bank
}

const getDirections = (bank) => {
    const address = `${bank.address}, ${bank.city} ${bank.postal_code}`
    const url = `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(address)}`
    window.open(url, '_blank')
}

const callBank = (bank) => {
    window.location.href = `tel:${bank.phone}`
}

// Lifecycle
onMounted(() => {
    // Charger Leaflet dynamiquement
    const link = document.createElement('link')
    link.rel = 'stylesheet'
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
    document.head.appendChild(link)

    const script = document.createElement('script')
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
    script.onload = () => {
        // Initialiser la carte après le chargement de Leaflet
        setTimeout(() => {
            initMap()
            fetchBanks()
        }, 100)
    }
    document.head.appendChild(script)
})

// Watcher pour mettre à jour la carte quand les banques changent
watch(filteredBanks, () => {
    updateMapMarkers()
}, { deep: true })
</script>
