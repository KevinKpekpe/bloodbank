@extends('layouts.app')

@section('title', 'Demandes de Sang - BloodLink')
@section('description', 'Gérez les demandes de sang pour vos patients.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Demandes de Sang</h1>
            <p class="mt-2 text-gray-600">Gérez les demandes de sang pour vos patients</p>
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

        <!-- Actions -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Liste des Demandes</h2>
            <a href="{{ route('blood-requests.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Nouvelle Demande
            </a>
        </div>

        <!-- Filtres -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Tous les statuts</option>
                        <option value="pending" @if(request('status') == 'pending') selected @endif>En attente</option>
                        <option value="approved" @if(request('status') == 'approved') selected @endif>Approuvée</option>
                        <option value="fulfilled" @if(request('status') == 'fulfilled') selected @endif>Satisfaite</option>
                        <option value="cancelled" @if(request('status') == 'cancelled') selected @endif>Annulée</option>
                    </select>
                </div>
                <div>
                    <label for="urgency" class="block text-sm font-medium text-gray-700 mb-1">Urgence</label>
                    <select name="urgency" id="urgency" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Tous les niveaux</option>
                        <option value="low" @if(request('urgency') == 'low') selected @endif>Faible</option>
                        <option value="medium" @if(request('urgency') == 'medium') selected @endif>Moyenne</option>
                        <option value="high" @if(request('urgency') == 'high') selected @endif>Élevée</option>
                        <option value="critical" @if(request('urgency') == 'critical') selected @endif>Critique</option>
                    </select>
                </div>
                <div>
                    <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-1">Type de Sang</label>
                    <select name="blood_type" id="blood_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Tous les types</option>
                        @foreach($bloodTypes as $type)
                            <option value="{{ $type->id }}" @if(request('blood_type') == $type->id) selected @endif>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Tableau des demandes -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($requests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Urgence
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date Requise
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->patient->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $request->patient->hospital_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $request->bloodType->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($request->quantity_ml) }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($request->urgency_level === 'critical') bg-red-100 text-red-800
                                            @elseif($request->urgency_level === 'high') bg-orange-100 text-orange-800
                                            @elseif($request->urgency_level === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($request->urgency_level === 'critical') Critique
                                            @elseif($request->urgency_level === 'high') Élevée
                                            @elseif($request->urgency_level === 'medium') Moyenne
                                            @else Faible
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'fulfilled') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($request->status === 'pending') En attente
                                            @elseif($request->status === 'approved') Approuvée
                                            @elseif($request->status === 'fulfilled') Satisfaite
                                            @else Annulée
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($request->required_date)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('blood-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900">
                                                Voir
                                            </a>
                                            @if($request->status === 'pending')
                                                <form method="POST" action="{{ route('blood-requests.cancel', $request->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                                        Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune demande</h3>
                    <p class="text-gray-500 mb-6">Aucune demande de sang n'a été créée pour le moment.</p>
                    <a href="{{ route('blood-requests.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Créer une demande
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
