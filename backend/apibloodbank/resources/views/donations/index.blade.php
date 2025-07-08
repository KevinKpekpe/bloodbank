@extends('layouts.app')

@section('title', 'Gestion des Dons - BloodLink')
@section('description', 'Planifiez vos dons et suivez votre historique.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Dons de Sang</h1>
            <p class="mt-2 text-gray-600">Planifiez vos dons et suivez votre historique</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Statistiques du donneur -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Dons Totaux</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['totalDonations'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Dernier Don</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['lastDonation'] ?? 'Aucun' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Prochain Don</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['nextDonation'] ?? 'Non planifié' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Éligible</p>
                        <p class="text-2xl font-bold text-gray-900">{{ ($stats['eligible'] ?? false) ? 'Oui' : 'Non' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <div class="bg-white rounded-lg shadow-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <a href="#history" class="border-red-500 text-red-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Historique des dons
                    </a>
                    <a href="{{ route('donations.book') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Prendre un rendez-vous
                    </a>
                </nav>
            </div>

            <!-- Contenu des onglets -->
            <div class="p-6">
                <!-- Onglet Historique -->
                <div id="history" class="space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Historique des dons</h3>
                        <div class="flex space-x-2">
                            <form method="GET" class="flex space-x-2">
                                <select name="status" class="rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Tous les dons</option>
                                    <option value="completed" @if(request('status') == 'completed') selected @endif>Complétés</option>
                                    <option value="scheduled" @if(request('status') == 'scheduled') selected @endif>Planifiés</option>
                                    <option value="cancelled" @if(request('status') == 'cancelled') selected @endif>Annulés</option>
                                </select>
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Filtrer
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        @if($donations->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($donations as $donation)
                                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $donation->bloodBank->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y H:i') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ ucfirst($donation->donation_type) }} - {{ $donation->bloodType->name ?? 'Non spécifié' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                    @if($donation->status == 'scheduled') bg-yellow-100 text-yellow-800
                                                    @elseif($donation->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    @if($donation->status == 'scheduled') Programmé
                                                    @elseif($donation->status == 'completed') Terminé
                                                    @else Annulé
                                                    @endif
                                                </span>
                                                @if($donation->status == 'scheduled')
                                                    <form method="POST" action="{{ route('donations.cancel', $donation->id) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                                                            Annuler
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="px-6 py-4 bg-gray-50">
                                {{ $donations->links() }}
                            </div>
                        @else
                            <div class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun don</h3>
                                <p class="text-gray-500 mb-6">Vous n'avez pas encore de dons enregistrés.</p>
                                <a href="{{ route('donations.book') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors">
                                    Prendre un rendez-vous
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
