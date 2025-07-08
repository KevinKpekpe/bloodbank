@extends('layouts.app')

@section('title', 'Banques de Sang - BloodLink')
@section('description', 'Trouvez la banque de sang la plus proche de chez vous.')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="{{ asset('css/blood-banks-map.css') }}" />
@endpush

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
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Rechercher par ville, code postal..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                    />
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Blood Type Filter -->
            <div>
                <select
                    id="bloodTypeFilter"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                >
                    <option value="">Tous les types de sang</option>
                    @foreach(\App\Models\BloodType::all() as $bloodType)
                        <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search Button -->
            <div>
                <button
                    id="searchBtn"
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
                >
                    <svg class="loading-spinner w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="searchText">Rechercher</span>
                </button>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-6">
            <button
                id="locationBtn"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors flex items-center justify-center"
            >
                <svg id="locationIcon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span id="locationText">Ma position</span>
            </button>
            <button
                id="showAllBtn"
                class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg font-medium transition-colors"
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
                            <svg class="animate-spin w-8 h-8 mx-auto mb-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modalContent" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/blood-banks-map.js') }}"></script>
@endpush
