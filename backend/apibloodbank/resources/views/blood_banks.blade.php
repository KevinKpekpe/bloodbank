@extends('layouts.app')

@section('title', 'Banques de sang - BloodLink')
@section('description', 'Trouvez une banque de sang près de chez vous et consultez les stocks disponibles.')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 0.5rem;
    }

    .bank-popup {
        max-width: 300px;
    }

    .bank-popup h3 {
        font-weight: bold;
        margin-bottom: 8px;
        color: #dc2626;
    }

    .bank-popup p {
        margin: 4px 0;
        font-size: 14px;
    }

    .distance-badge {
        background: #dc2626;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    .stock-info {
        background: #f3f4f6;
        padding: 8px;
        border-radius: 4px;
        margin-top: 8px;
    }

    .stock-low {
        background: #fef2f2;
        color: #dc2626;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    .loading-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }
</style>

@section('content')
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
                        <input id="searchInput" type="text" placeholder="Rechercher par ville, code postal..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors" />
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Blood Type Filter -->
                <div>
                    <select id="bloodTypeFilter"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
                        <option value="">Tous les types de sang</option>
                        @foreach (\App\Models\BloodType::all() as $bloodType)
                            <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div>
                    <button id="searchBtn"
                        class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="loading-spinner w-5 h-5 mr-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span id="searchText">Rechercher</span>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-6">
                <button id="locationBtn"
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                    <svg id="locationIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span id="locationText">Ma position</span>
                </button>
                <button id="showAllBtn"
                    class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-colors">
                    Voir toutes les banques
                </button>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map Container -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Carte des banques de sang</h2>
                            <p class="text-gray-600" id="mapInfo">Chargement...</p>
                        </div>
                        <div id="map"></div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Banques de sang</h3>
                            <p class="text-sm text-gray-600" id="resultsCount">Chargement...</p>
                        </div>
                        <div class="max-h-96 overflow-y-auto" id="resultsList">
                            <div class="p-6 text-center text-gray-500">
                                <svg class="animate-spin w-8 h-8 mx-auto mb-4 text-red-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p>Chargement des banques...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bank Details Modal -->
    <div id="bankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-900" id="modalBankName"></h3>
                    <button onclick="closeBankModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Données des banques de sang depuis le serveur
        const bloodBanksData = @json($bloodBanks->items());
        console.log('=== DÉBUT SCRIPT CARTE ===');
        console.log('Données des banques:', bloodBanksData);

        class BloodBanksMap {
            constructor() {
                console.log('=== CONSTRUCTEUR BloodBanksMap ===');
                this.map = null;
                this.markers = [];
                this.userMarker = null;
                this.userLocation = null;
                this.currentBanks = bloodBanksData;

                this.init();
            }

            /**
             * Initialisation de la carte
             */
            init() {
                console.log('=== INITIALISATION CARTE ===');

                try {
                    // Centre par défaut (France)
                    const defaultCenter = [46.603354, 1.888334];
                    console.log('Centre par défaut:', defaultCenter);

                    const mapElement = document.getElementById('map');
                    console.log('Élément map trouvé:', mapElement);

                    if (!mapElement) {
                        console.error('Élément #map non trouvé!');
                        return;
                    }

                    this.map = L.map('map').setView(defaultCenter, 6);
                    console.log('Carte Leaflet créée:', this.map);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.map);
                    console.log('Couche de tuiles ajoutée');

                    // Charger les banques initiales
                    console.log('Ajout des marqueurs des banques...');
                    this.addBankMarkers(this.currentBanks);
                    this.updateResults();

                    // Événements
                    this.bindEvents();
                    console.log('=== INITIALISATION TERMINÉE ===');

                } catch (error) {
                    console.error('Erreur lors de l\'initialisation:', error);
                }
            }

            /**
             * Ajouter les marqueurs des banques
             */
            addBankMarkers(banks) {
                console.log('=== AJOUT MARQUEURS ===');
                console.log('Banques à ajouter:', banks);

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

                banks.forEach((bank, index) => {
                    console.log(`Traitement banque ${index + 1}:`, bank);

                    if (bank && bank.latitude && bank.longitude) {
                        try {
                            const lat = parseFloat(bank.latitude);
                            const lng = parseFloat(bank.longitude);
                            console.log(`Coordonnées: ${lat}, ${lng}`);

                            const marker = L.marker([lat, lng])
                                .addTo(this.map)
                                .bindPopup(this.createBankPopup(bank));

                            this.markers.push(marker);
                            console.log(`Marqueur ajouté pour ${bank.name}`);
                        } catch (error) {
                            console.error('Erreur lors de la création du marqueur:', error);
                        }
                    } else {
                        console.warn(`Banque sans coordonnées: ${bank.name}`);
                    }
                });

                console.log(`Total marqueurs créés: ${this.markers.length}`);

                // Ajuster la vue si on a des marqueurs
                if (this.markers.length > 0) {
                    try {
                        const group = new L.featureGroup(this.markers);
                        this.map.fitBounds(group.getBounds().pad(0.1));
                        console.log('Vue ajustée aux marqueurs');
                    } catch (error) {
                        console.error('Erreur lors de l\'ajustement de la vue:', error);
                    }
                } else {
                    console.warn('Aucun marqueur à afficher');
                }
            }

            /**
             * Créer le contenu du popup
             */
            createBankPopup(bank) {
                if (!bank) return '';

                const totalStock = bank.blood_stocks ? bank.blood_stocks.reduce((sum, stock) => sum + (stock
                    .quantity_ml || 0), 0) : 0;
                const hasLowStock = bank.blood_stocks ? bank.blood_stocks.some(stock => (stock.quantity_ml || 0) <=
                    1000) : false;
                const stockBadge = hasLowStock ? '<span class="stock-low">Stock faible</span>' : '';

                return `
<div class="bank-popup">
<h3>${bank.name || 'Banque de sang'}</h3>
<p>${bank.address || ''}</p>
<p>${bank.city || ''}, ${bank.postal_code || ''}</p>
<p>📞 ${bank.phone || 'Non renseigné'}</p>
<p>✉️ ${bank.email || 'Non renseigné'}</p>
<div class="stock-info">
<strong>Stock total:</strong> ${totalStock.toLocaleString()} ml
${stockBadge}
</div>
<button onclick="window.bloodBanksMap.getDirections(${parseFloat(bank.latitude)}, ${parseFloat(bank.longitude)})"
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
                    let url = '/blood-banks';
                    const params = new URLSearchParams();

                    if (searchQuery) params.append('search', searchQuery);
                    if (bloodType) params.append('blood_type_id', bloodType);

                    if (params.toString()) {
                        url += '?' + params.toString();
                    }

                    const response = await fetch(url);
                    const html = await response.text();

                    // Extraire les données des banques depuis la réponse HTML
                    // Pour l'instant, on utilise les données initiales et on filtre côté client
                    this.filterBanks(searchQuery, bloodType);
                } catch (error) {
                    console.error('Erreur lors de la recherche:', error);
                } finally {
                    this.setLoading(false);
                }
            }

            /**
             * Filtrer les banques côté client
             */
            filterBanks(searchQuery, bloodType) {
                let filteredBanks = [...bloodBanksData];

                if (searchQuery) {
                    const searchLower = searchQuery.toLowerCase();
                    filteredBanks = filteredBanks.filter(bank =>
                        bank.name.toLowerCase().includes(searchLower) ||
                        bank.city.toLowerCase().includes(searchLower) ||
                        bank.address.toLowerCase().includes(searchLower)
                    );
                }

                if (bloodType) {
                    filteredBanks = filteredBanks.filter(bank =>
                        bank.blood_stocks && bank.blood_stocks.some(stock =>
                            stock.blood_type_id == bloodType && stock.quantity_ml > 0
                        )
                    );
                }

                this.currentBanks = filteredBanks;
                this.addBankMarkers(filteredBanks);
                this.updateResults();
            }

            /**
             * Obtenir la position actuelle
             */
            getCurrentLocation() {
                if (!navigator.geolocation) {
                    alert('La géolocalisation n\'est pas supportée par votre navigateur.');
                    return;
                }

                this.setLocationLoading(true);

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        this.userLocation = {
                            lat,
                            lng
                        };
                        this.addUserMarker(lat, lng);
                        this.searchNearby(lat, lng);
                        this.resetLocationButton();
                    },
                    (error) => {
                        console.error('Erreur de géolocalisation:', error);
                        alert('Impossible d\'obtenir votre position. Vérifiez les permissions de géolocalisation.');
                        this.resetLocationButton();
                    }
                );
            }

            /**
             * Ajouter le marqueur de l'utilisateur
             */
            addUserMarker(lat, lng) {
                if (this.userMarker) {
                    this.map.removeLayer(this.userMarker);
                }

                this.userMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'user-marker',
                        html: '<div style="background: #3b82f6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    })
                }).addTo(this.map);

                this.map.setView([lat, lng], 12);
            }

            /**
             * Rechercher par proximité
             */
            async searchNearby(lat, lng) {
                this.setLoading(true);

                try {
                    const response = await fetch('/blood-banks/search/nearby', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            latitude: lat,
                            longitude: lng,
                            radius_km: 50
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.currentBanks = data.data || [];
                        this.addBankMarkers(this.currentBanks);
                        this.updateResults();
                    }
                } catch (error) {
                    console.error('Erreur lors de la recherche par proximité:', error);
                } finally {
                    this.setLoading(false);
                }
            }

            /**
             * Réinitialiser le bouton de localisation
             */
            resetLocationButton() {
                const locationBtn = document.getElementById('locationBtn');
                const locationIcon = document.getElementById('locationIcon');
                const locationText = document.getElementById('locationText');

                if (locationBtn) {
                    locationBtn.disabled = false;
                    locationIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>';
                    locationText.textContent = 'Ma position';
                }
            }

            /**
             * Mettre à jour la liste des résultats
             */
            updateResults() {
                const resultsList = document.getElementById('resultsList');
                const resultsCount = document.getElementById('resultsCount');
                const mapInfo = document.getElementById('mapInfo');

                if (!resultsList || !resultsCount || !mapInfo) return;

                const count = this.currentBanks.length;
                resultsCount.textContent = `${count} banque${count > 1 ? 's' : ''} trouvée${count > 1 ? 's' : ''}`;
                mapInfo.textContent =
                    `${count} banque${count > 1 ? 's' : ''} affichée${count > 1 ? 's' : ''} sur la carte`;

                if (count === 0) {
                    resultsList.innerHTML = `
<div class="p-6 text-center text-gray-500">
<svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
</svg>
<p class="text-lg font-medium">Aucune banque trouvée</p>
<p class="text-sm">Essayez de modifier vos critères de recherche</p>
</div>
`;
                    return;
                }

                const banksHtml = this.currentBanks.map(bank => {
                    const totalStock = bank.blood_stocks ? bank.blood_stocks.reduce((sum, stock) => sum + (stock
                        .quantity_ml || 0), 0) : 0;
                    const hasLowStock = bank.blood_stocks ? bank.blood_stocks.some(stock => (stock
                        .quantity_ml || 0) <= 1000) : false;
                    const stockBadge = hasLowStock ? '<span class="stock-low">Stock faible</span>' : '';

                    return `
<div class="p-4 border-b border-gray-200 hover:bg-gray-50 cursor-pointer" onclick="window.bloodBanksMap.showBankDetails(${bank.id})">
<h4 class="font-semibold text-gray-900">${bank.name}</h4>
<p class="text-sm text-gray-600">${bank.address}, ${bank.city}</p>
<p class="text-sm text-gray-600">📞 ${bank.phone || 'Non renseigné'}</p>
<div class="mt-2 flex items-center justify-between">
<span class="text-sm font-medium">Stock: ${totalStock.toLocaleString()} ml</span>
${stockBadge}
</div>
</div>
`;
                }).join('');

                resultsList.innerHTML = banksHtml;
            }

            /**
             * Afficher toutes les banques
             */
            showAllBanks() {
                this.currentBanks = bloodBanksData;
                this.addBankMarkers(this.currentBanks);
                this.updateResults();

                // Réinitialiser les filtres
                document.getElementById('searchInput').value = '';
                document.getElementById('bloodTypeFilter').value = '';
            }

            /**
             * Obtenir l'itinéraire
             */
            getDirections(lat, lng) {
                const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
                window.open(url, '_blank');
            }

            /**
             * Afficher les détails d'une banque
             */
            showBankDetails(bankId) {
                const bank = this.currentBanks.find(b => b.id === bankId);
                if (!bank) return;

                const modal = document.getElementById('bankModal');
                const modalName = document.getElementById('modalBankName');
                const modalContent = document.getElementById('modalContent');

                modalName.textContent = bank.name;

                const totalStock = bank.blood_stocks ? bank.blood_stocks.reduce((sum, stock) => sum + (stock
                    .quantity_ml || 0), 0) : 0;
                const hasLowStock = bank.blood_stocks ? bank.blood_stocks.some(stock => (stock.quantity_ml || 0) <=
                    1000) : false;

                modalContent.innerHTML = `
<div class="space-y-4">
<div>
<h4 class="font-semibold text-gray-900">Adresse</h4>
<p class="text-gray-600">${bank.address}</p>
<p class="text-gray-600">${bank.city}, ${bank.postal_code}</p>
</div>
<div>
<h4 class="font-semibold text-gray-900">Contact</h4>
<p class="text-gray-600">📞 ${bank.phone || 'Non renseigné'}</p>
<p class="text-gray-600">✉️ ${bank.email || 'Non renseigné'}</p>
</div>
<div>
<h4 class="font-semibold text-gray-900">Stock total</h4>
<p class="text-gray-600">${totalStock.toLocaleString()} ml</p>
${hasLowStock ? '<p class="stock-low">Stock faible</p>' : ''}
</div>
<div class="flex space-x-4">
<button onclick="window.bloodBanksMap.getDirections(${parseFloat(bank.latitude)}, ${parseFloat(bank.longitude)})"
class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
Itinéraire
</button>
${bank.phone ? `<a href="tel:${bank.phone}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">Appeler</a>` : ''}
</div>
</div>
`;

                modal.classList.remove('hidden');
            }

            /**
             * Gérer le chargement
             */
            setLoading(loading) {
                const searchBtn = document.getElementById('searchBtn');
                const searchText = document.getElementById('searchText');
                const spinner = document.querySelector('.loading-spinner');

                if (loading) {
                    searchBtn.disabled = true;
                    searchText.textContent = 'Recherche...';
                    spinner.classList.remove('hidden');
                } else {
                    searchBtn.disabled = false;
                    searchText.textContent = 'Rechercher';
                    spinner.classList.add('hidden');
                }
            }

            /**
             * Gérer le chargement de la localisation
             */
            setLocationLoading(loading) {
                const locationBtn = document.getElementById('locationBtn');
                const locationText = document.getElementById('locationText');
                const locationIcon = document.getElementById('locationIcon');

                if (loading) {
                    locationBtn.disabled = true;
                    locationText.textContent = 'Localisation...';
                    locationIcon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" class="animate-spin"/>';
                }
            }

            /**
             * Lier les événements
             */
            bindEvents() {
                const searchBtn = document.getElementById('searchBtn');
                const locationBtn = document.getElementById('locationBtn');
                const showAllBtn = document.getElementById('showAllBtn');
                const searchInput = document.getElementById('searchInput');

                if (searchBtn) {
                    searchBtn.addEventListener('click', () => this.searchBanks());
                }

                if (locationBtn) {
                    locationBtn.addEventListener('click', () => this.getCurrentLocation());
                }

                if (showAllBtn) {
                    showAllBtn.addEventListener('click', () => this.showAllBanks());
                }

                if (searchInput) {
                    searchInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            this.searchBanks();
                        }
                    });
                }
            }
        }

        // Fermer la modal
        function closeBankModal() {
            document.getElementById('bankModal').classList.add('hidden');
        }

        // Initialiser la carte quand le DOM est chargé
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== DOM CHARGÉ - INITIALISATION CARTE ===');
            try {
                window.bloodBanksMap = new BloodBanksMap();
                console.log('=== CARTE INITIALISÉE AVEC SUCCÈS ===');
            } catch (error) {
                console.error('Erreur lors de l\'initialisation de la carte:', error);
            }
        });
    </script>
@endsection
