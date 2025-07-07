<template>
    <div>
        <div id="blood-bank-map" class="w-full h-96 rounded-lg"></div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

// Props
const props = defineProps({
    banks: {
        type: Array,
        default: () => []
    },
    userLocation: {
        type: Object,
        default: null
    }
})

// Emits
const emit = defineEmits(['bank-selected'])

// Reactive data
const map = ref(null)
const markers = ref([])

// Initialize map
const initMap = () => {
    // Remove existing map
    if (map.value) {
        map.value.remove()
    }

    // Initialize map centered on France
    map.value = L.map('blood-bank-map').setView([46.603354, 1.888334], 6)

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map.value)

    // Add markers for banks
    addBankMarkers()

    // Add user location marker if available
    if (props.userLocation) {
        addUserLocationMarker()
    }
}

// Add bank markers
const addBankMarkers = () => {
    // Clear existing markers
    markers.value.forEach(marker => marker.remove())
    markers.value = []

    props.banks.forEach(bank => {
        const marker = L.marker([bank.latitude, bank.longitude])
            .addTo(map.value)
            .bindPopup(`
                <div class="p-2">
                    <h3 class="font-semibold text-gray-900">${bank.name}</h3>
                    <p class="text-sm text-gray-600">${bank.address}</p>
                    <p class="text-sm text-gray-500">${bank.city}, ${bank.postal_code}</p>
                    <p class="text-sm text-gray-500">Distance: ${bank.distance} km</p>
                    <button onclick="selectBankFromMap(${bank.id})" class="mt-2 text-red-600 hover:text-red-700 text-sm font-medium">
                        Voir détails
                    </button>
                </div>
            `)

        // Add click event
        marker.on('click', () => {
            emit('bank-selected', bank)
        })

        markers.value.push(marker)
    })
}

// Add user location marker
const addUserLocationMarker = () => {
    if (!props.userLocation) return

    const userIcon = L.divIcon({
        className: 'user-location-marker',
        html: '<div class="w-6 h-6 bg-blue-500 rounded-full border-2 border-white shadow-lg"></div>',
        iconSize: [24, 24],
        iconAnchor: [12, 12]
    })

    const userMarker = L.marker([props.userLocation.lat, props.userLocation.lng], { icon: userIcon })
        .addTo(map.value)
        .bindPopup('Votre position')
        .openPopup()

    markers.value.push(userMarker)
}

// Fit map to show all markers
const fitMapToMarkers = () => {
    if (markers.value.length > 0) {
        const group = new L.featureGroup(markers.value)
        map.value.fitBounds(group.getBounds().pad(0.1))
    }
}

// Watch for changes in banks
watch(() => props.banks, () => {
    if (map.value) {
        addBankMarkers()
        fitMapToMarkers()
    }
}, { deep: true })

// Watch for changes in user location
watch(() => props.userLocation, () => {
    if (map.value && props.userLocation) {
        addUserLocationMarker()
        map.value.setView([props.userLocation.lat, props.userLocation.lng], 12)
    }
})

// Lifecycle
onMounted(() => {
    // Initialize map after component is mounted
    setTimeout(() => {
        initMap()
    }, 100)
})

// Expose methods to parent
defineExpose({
    fitMapToMarkers,
    setView: (lat, lng, zoom) => {
        if (map.value) {
            map.value.setView([lat, lng], zoom || 12)
        }
    }
})
</script>

<style scoped>
/* Custom styles for the map */
:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-content) {
    margin: 8px;
    font-family: 'Inter', sans-serif;
}

:deep(.leaflet-popup-tip) {
    background: white;
}

.user-location-marker {
    background: transparent;
    border: none;
}
</style>