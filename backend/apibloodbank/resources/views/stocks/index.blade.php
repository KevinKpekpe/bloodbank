@extends('layouts.app')

@section('title', 'Gestion des Stocks - BloodLink')
@section('description', 'Gérez les stocks de sang de votre banque.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Stocks de Sang</h1>
            <p class="mt-2 text-gray-600">Surveillez et gérez les stocks de votre banque de sang</p>
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
            <h2 class="text-xl font-semibold text-gray-900">Stocks de Sang</h2>
            <a href="{{ route('stocks.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Ajouter un stock
            </a>
        </div>

        <!-- Tableau des stocks -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($stocks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Banque de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type de Sang
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantité (ml)
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($stocks as $stock)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $stock->bloodBank->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $stock->bloodType->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ number_format($stock->quantity_ml) }} ml
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($stock->quantity_ml == 0) bg-red-100 text-red-800
                                            @elseif($stock->quantity_ml <= $stock->minimum_threshold) bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            @if($stock->quantity_ml == 0) Vide
                                            @elseif($stock->quantity_ml <= $stock->minimum_threshold) Faible
                                            @else Disponible
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('stocks.edit', $stock->id) }}" class="text-blue-600 hover:text-blue-900">
                                            Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50">
                    {{ $stocks->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun stock</h3>
                    <p class="text-gray-500 mb-6">Aucun stock de sang n'a été enregistré pour le moment.</p>
                    <a href="{{ route('stocks.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors">
                        Ajouter un stock
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection