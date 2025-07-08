@extends('layouts.app')

@section('title', 'Détails de la Demande - BloodLink')
@section('description', 'Détails de la demande de sang.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Demande de Sang #{{ $bloodRequest->id }}</h1>
                    <p class="mt-2 text-gray-600">Détails de la demande</p>
                </div>
                <a href="{{ route('blood-requests.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Retour à la liste
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informations principales -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations de la Demande</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Patient</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Nom:</span> {{ $bloodRequest->patient->name ?? 'N/A' }}</p>
                        <p><span class="font-medium">Hôpital:</span> {{ $bloodRequest->patient->hospital_name ?? 'N/A' }}</p>
                        <p><span class="font-medium">Type de sang:</span> {{ $bloodRequest->patient->bloodType->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Demande</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Type requis:</span> {{ $bloodRequest->bloodType->name ?? 'N/A' }}</p>
                        <p><span class="font-medium">Quantité:</span> {{ number_format($bloodRequest->quantity_ml) }} ml</p>
                        <p><span class="font-medium">Date requise:</span> {{ \Carbon\Carbon::parse($bloodRequest->required_date)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Niveau d'urgence</span>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($bloodRequest->urgency_level === 'critical') bg-red-100 text-red-800
                            @elseif($bloodRequest->urgency_level === 'high') bg-orange-100 text-orange-800
                            @elseif($bloodRequest->urgency_level === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            @if($bloodRequest->urgency_level === 'critical') Critique
                            @elseif($bloodRequest->urgency_level === 'high') Élevée
                            @elseif($bloodRequest->urgency_level === 'medium') Moyenne
                            @else Faible
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-500">Statut</span>
                    <div class="mt-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($bloodRequest->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($bloodRequest->status === 'approved') bg-blue-100 text-blue-800
                            @elseif($bloodRequest->status === 'fulfilled') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($bloodRequest->status === 'pending') En attente
                            @elseif($bloodRequest->status === 'approved') Approuvée
                            @elseif($bloodRequest->status === 'fulfilled') Satisfaite
                            @else Annulée
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    <span class="text-sm font-medium text-gray-500">Demandé par</span>
                    <div class="mt-1">
                        <p class="text-sm text-gray-900">{{ $bloodRequest->requestedBy->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($bloodRequest->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($bloodRequest->reason)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Raison de la demande</h3>
                    <p class="text-gray-700">{{ $bloodRequest->reason }}</p>
                </div>
            @endif

            @if($bloodRequest->notes)
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Notes</h3>
                    <p class="text-gray-700">{{ $bloodRequest->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Satisfactions -->
        @if($bloodRequest->fulfillments->count() > 0)
            <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Satisfactions</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Banque de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité (ml)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date de Satisfaction
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bloodRequest->fulfillments as $fulfillment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $fulfillment->bloodBank->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($fulfillment->quantity_ml) }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($fulfillment->fulfillment_date)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($fulfillment->status === 'completed') bg-green-100 text-green-800
                                            @elseif($fulfillment->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($fulfillment->status === 'completed') Terminée
                                            @elseif($fulfillment->status === 'pending') En cours
                                            @else Annulée
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Actions -->
        @if($bloodRequest->status === 'pending')
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>

                <div class="flex space-x-4">
                    <form method="POST" action="{{ route('blood-requests.cancel', $bloodRequest->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                            Annuler la demande
                        </button>
                    </form>

                    <a href="{{ route('blood-requests.search-availability', $bloodRequest->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Rechercher disponibilités
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
