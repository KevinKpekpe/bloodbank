@extends('layouts.app')

@section('title', 'Mouvements de Stock - BloodLink')
@section('description', 'Historique des mouvements de stock de sang.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mouvements de Stock</h1>
            <p class="mt-2 text-gray-600">Historique des entrées et sorties de stock</p>
        </div>

        <!-- Actions -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Historique des Mouvements</h2>
            <a href="{{ route('stocks.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Retour aux stocks
            </a>
        </div>

        <!-- Tableau des mouvements -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($movements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Banque de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de Mouvement
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité (ml)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Raison
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($movements as $movement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $movement->bloodBank->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $movement->bloodType->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($movement->movement_type === 'in') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            @if($movement->movement_type === 'in') Entrée
                                            @else Sortie
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($movement->quantity_ml) }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $movement->reason }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50">
                    {{ $movements->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun mouvement</h3>
                    <p class="text-gray-500 mb-6">Aucun mouvement de stock n'a été enregistré pour le moment.</p>
                    <a href="{{ route('stocks.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Retour aux stocks
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
