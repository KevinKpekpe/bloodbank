/**
 * Blood Banks Map - Géolocalisation des banques de sang
 * Utilise les contrôleurs existants et les données en base
 */

class BloodBanksMap {
    constructor() {
        this.map = null;
        this.markers = [];
        this.userMarker = null;
        this.userLocation = null;
        this.currentBanks = [];

        this.init();
    }

    /**
     * Initialisation de la carte
     */
    init() {
        // Centre par défaut (France)
        const defaultCenter = [46.603354, 1.888334];

        this.map = L.map('map').setView(defaultCenter, 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);

        // Charger les banques initiales
        this.loadInitialBanks();

        // Événements
        this.bindEvents();
    }

    /**
     * Charger les banques initiales depuis le contrôleur
     */
    async loadInitialBanks() {
        try {
            const response = await fetch('/api/blood-banks');
            const data = await response.json();

            if (data.blood_banks && data.blood_banks.data) {
                this.currentBanks = data.blood_banks.data;
                this.addBankMarkers(this.currentBanks);
                this.updateResults();
            }
        } catch (error) {
            console.error('Erreur lors du chargement des banques:', error);
        }
    }

    /**
     * Ajouter les marqueurs des banques
     */
    addBankMarkers(banks) {
        // Supprimer les anciens marqueurs
        this.markers.forEach(marker => {
            if (marker && this.map) {
                this.map.removeLayer(marker);
            }
        });
        this.markers = [];

        if (!Array.isArray(banks)) {
            console.error('banks n\'est pas un tableau:', banks);
            return;
        }

        banks.forEach(bank => {
            if (bank && bank.latitude && bank.longitude) {
                try {
                    const marker = L.marker([parseFloat(bank.latitude), parseFloat(bank.longitude)])
                        .addTo(this.map)
                        .bindPopup(this.createBankPopup(bank));

                    this.markers.push(marker);
                } catch (error) {
                    console.error('Erreur lors de la création du marqueur:', error);
                }
            }
        });

        // Ajuster la vue si on a des marqueurs
        if (this.markers.length > 0) {
            try {
                const group = new L.featureGroup(this.markers);
                this.map.fitBounds(group.getBounds().pad(0.1));
            } catch (error) {
                console.error('Erreur lors de l\'ajustement de la vue:', error);
            }
        }
    }

    /**
     * Créer le contenu du popup
     */
    createBankPopup(bank) {
        if (!bank) return '';

        const distance = bank.distance_km ? `${bank.distance_km} km` : '';
        const distanceBadge = distance ? `<span class="distance-badge">${distance}</span>` : '';
        const lat = parseFloat(bank.latitude) || 0;
        const lng = parseFloat(bank.longitude) || 0;

        return `
            <div class="bank-popup">
                <h3>${bank.name || 'Banque de sang'}</h3>
                <p>${bank.address || ''}</p>
                <p>${bank.city || ''}, ${bank.postal_code || ''}</p>
                <p>📞 ${bank.phone || ''}</p>
                <p>✉️ ${bank.email || ''}</p>
                ${distanceBadge}
                <button onclick="window.bloodBanksMap.getDirections(${lat}, ${lng})"
                        style="background: #dc2626; color: white; padding: 4px 8px; border-radius: 4px; border: none; margin-top: 8px; cursor: pointer;">
                    Itinéraire
                </button>
            </div>
        `;
    }

    /**
     * Rechercher des banques
     */
    async searchBanks() {
        const searchInput = document.getElementById('searchInput');
        const bloodTypeFilter = document.getElementById('bloodTypeFilter');

        if (!searchInput || !bloodTypeFilter) {
            console.error('Éléments de recherche non trouvés');
            return;
        }

        const searchQuery = searchInput.value;
        const bloodType = bloodTypeFilter.value;

        this.setLoading(true);

        try {
            let url = '/api/blood-banks';
            const params = new URLSearchParams();

            if (searchQuery) params.append('search', searchQuery);
            if (bloodType) params.append('blood_type_id', bloodType);
            if (this.userLocation) {
                params.append('latitude', this.userLocation.lat);
                params.append('longitude', this.userLocation.lng);
                params.append('radius', 50);
            }

            if (params.toString()) {
                url += '?' + params.toString();
            }

            const response = await fetch(url);
            const data = await response.json();

            if (data.blood_banks) {
                this.currentBanks = data.blood_banks.data || data.blood_banks;
                this.updateResults();
                this.addBankMarkers(this.currentBanks);
            }
        } catch (error) {
            console.error('Erreur lors de la recherche:', error);
        } finally {
            this.setLoading(false);
        }
    }

    /**
     * Rechercher par géolocalisation
     */
    async searchNearby(lat, lng) {
        this.setLoading(true);

        try {
            const response = await fetch('/api/geolocation/nearby-banks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    radius_km: 50
                })
            });

            const data = await response.json();

            if (data.success) {
                this.currentBanks = data.banks || [];
                this.updateResults();
                this.addBankMarkers(this.currentBanks);
                this.addUserMarker(lat, lng);
            }
        } catch (error) {
            console.error('Erreur lors de la recherche par proximité:', error);
        } finally {
            this.setLoading(false);
        }
    }

    /**
     * Ajouter le marqueur de l'utilisateur
     */
    addUserMarker(lat, lng) {
        try {
            // Supprimer l'ancien marqueur utilisateur s'il existe
            if (this.userMarker && this.map) {
                this.map.removeLayer(this.userMarker);
            }

            this.userMarker = L.marker([parseFloat(lat), parseFloat(lng)], {
                icon: L.divIcon({
                    className: 'user-marker',
                    html: '<div style="background: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                })
            }).addTo(this.map);

            // Centrer la carte sur l'utilisateur
            this.map.setView([parseFloat(lat), parseFloat(lng)], 10);
        } catch (error) {
            console.error('Erreur lors de l\'ajout du marqueur utilisateur:', error);
        }
    }

    /**
     * Obtenir la position de l'utilisateur
     */
    getCurrentLocation() {
        const btn = document.getElementById('locationBtn');
        const icon = document.getElementById('locationIcon');
        const text = document.getElementById('locationText');

        if (!btn || !icon || !text) {
            console.error('Éléments de localisation non trouvés');
            return;
        }

        btn.disabled = true;
        text.textContent = 'Localisation...';
        icon.innerHTML = `
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        `;
        icon.classList.add('animate-spin');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    this.userLocation = { lat, lng };
                    this.searchNearby(lat, lng);
                    this.resetLocationButton();
                },
                (error) => {
                    console.error('Erreur de géolocalisation:', error);
                    alert('Impossible de récupérer votre position');
                    this.resetLocationButton();
                }
            );
        } else {
            alert('La géolocalisation n\'est pas supportée par votre navigateur');
            this.resetLocationButton();
        }
    }

    /**
     * Réinitialiser le bouton de localisation
     */
    resetLocationButton() {
        const btn = document.getElementById('locationBtn');
        const icon = document.getElementById('locationIcon');
        const text = document.getElementById('locationText');

        if (!btn || !icon || !text) return;

        btn.disabled = false;
        text.textContent = 'Ma position';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        `;
        icon.classList.remove('animate-spin');
    }

    /**
     * Mettre à jour les résultats
     */
    updateResults() {
        const resultsList = document.getElementById('resultsList');
        const resultsCount = document.getElementById('resultsCount');
        const mapInfo = document.getElementById('mapInfo');

        if (!resultsList || !resultsCount || !mapInfo) {
            console.error('Éléments de résultats non trouvés');
            return;
        }

        const bankCount = Array.isArray(this.currentBanks) ? this.currentBanks.length : 0;
        resultsCount.textContent = `${bankCount} résultat(s)`;
        mapInfo.textContent = `${bankCount} banque(s) trouvée(s)`;

        if (bankCount === 0) {
            resultsList.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.08-2.33"/>
                    </svg>
                    <p>Aucune banque trouvée</p>
                    <p class="text-sm">Essayez de modifier vos critères de recherche</p>
                </div>
            `;
            return;
        }

        let html = '';
        this.currentBanks.forEach(bank => {
            if (!bank) return;

            const distance = bank.distance_km ? `${bank.distance_km} km` : (bank.city || '');
            const lat = parseFloat(bank.latitude) || 0;
            const lng = parseFloat(bank.longitude) || 0;

            html += `
                <div class="bank-item p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors" data-bank-id="${bank.id || ''}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${bank.name || 'Banque de sang'}</h4>
                            <p class="text-sm text-gray-600">${bank.address || ''}</p>
                            <p class="text-sm text-gray-500">${bank.city || ''}, ${bank.postal_code || ''}</p>
                            <div class="flex items-center mt-2">
                                <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-600">${distance}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center space-x-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Ouvert
                                </span>
                            </div>
                            <button
                                onclick="window.bloodBanksMap.getDirections(${lat}, ${lng})"
                                class="mt-2 text-sm text-red-600 hover:text-red-700 font-medium transition-colors"
                            >
                                Itinéraire
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        resultsList.innerHTML = html;
    }

    /**
     * Afficher toutes les banques
     */
    showAllBanks() {
        this.userLocation = null;

        const searchInput = document.getElementById('searchInput');
        const bloodTypeFilter = document.getElementById('bloodTypeFilter');

        if (searchInput) searchInput.value = '';
        if (bloodTypeFilter) bloodTypeFilter.value = '';

        // Supprimer le marqueur utilisateur
        if (this.userMarker && this.map) {
            this.map.removeLayer(this.userMarker);
            this.userMarker = null;
        }

        this.loadInitialBanks();
    }

    /**
     * Obtenir l'itinéraire
     */
    getDirections(lat, lng) {
        try {
            const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
            window.open(url, '_blank');
        } catch (error) {
            console.error('Erreur lors de l\'ouverture de l\'itinéraire:', error);
        }
    }

    /**
     * Gérer le chargement
     */
    setLoading(loading) {
        const btn = document.getElementById('searchBtn');
        if (!btn) return;

        const spinner = btn.querySelector('.loading-spinner');
        const text = document.getElementById('searchText');

        if (!spinner || !text) return;

        if (loading) {
            btn.disabled = true;
            spinner.classList.add('active');
            text.textContent = 'Recherche...';
        } else {
            btn.disabled = false;
            spinner.classList.remove('active');
            text.textContent = 'Rechercher';
        }
    }

    /**
     * Lier les événements
     */
    bindEvents() {
        // Recherche
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');
        const bloodTypeFilter = document.getElementById('bloodTypeFilter');
        const locationBtn = document.getElementById('locationBtn');
        const showAllBtn = document.getElementById('showAllBtn');

        if (searchBtn) {
            searchBtn.addEventListener('click', () => this.searchBanks());
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') this.searchBanks();
            });
        }

        if (bloodTypeFilter) {
            bloodTypeFilter.addEventListener('change', () => this.searchBanks());
        }

        // Géolocalisation
        if (locationBtn) {
            locationBtn.addEventListener('click', () => this.getCurrentLocation());
        }

        if (showAllBtn) {
            showAllBtn.addEventListener('click', () => this.showAllBanks());
        }
    }
}

// Initialiser la carte quand le DOM est prêt
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.bloodBanksMap = new BloodBanksMap();
    } catch (error) {
        console.error('Erreur lors de l\'initialisation de la carte:', error);
    }
});
