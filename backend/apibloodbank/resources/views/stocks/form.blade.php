@extends('layouts.app')

@section('title', isset($stock) ? 'Modifier le Stock - BloodLink' : 'Ajouter un Stock - BloodLink')
@section('description', 'Gérez les stocks de sang de votre banque.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ isset($stock) ? 'Modifier le Stock' : 'Ajouter un Stock' }}
            </h1>
            <p class="mt-2 text-gray-600">
                {{ isset($stock) ? 'Modifiez les informations du stock de sang' : 'Ajoutez un nouveau stock de sang' }}
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <form method="POST" action="{{ isset($stock) ? route('stocks.update', $stock->id) : route('stocks.store') }}">
                @csrf
                @if(isset($stock))
                    @method('PUT')
                @endif

                <!-- Banque de sang -->
                <div class="mb-6">
                    <label for="blood_bank_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Banque de Sang *
                    </label>
                    <select name="blood_bank_id" id="blood_bank_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionnez une banque de sang</option>
                        @foreach($bloodBanks as $bank)
                            <option value="{{ $bank->id }}" {{ (old('blood_bank_id', $stock->blood_bank_id ?? '') == $bank->id) ? 'selected' : '' }}>
                                {{ $bank->name }} - {{ $bank->city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type de sang -->
                <div class="mb-6">
                    <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de Sang *
                    </label>
                    <select name="blood_type_id" id="blood_type_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionnez un type de sang</option>
                        @foreach($bloodTypes as $type)
                            <option value="{{ $type->id }}" {{ (old('blood_type_id', $stock->blood_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantité -->
                <div class="mb-6">
                    <label for="quantity_ml" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantité (ml) *
                    </label>
                    <input type="number" name="quantity_ml" id="quantity_ml"
                           value="{{ old('quantity_ml', $stock->quantity_ml ?? 0) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                           min="0" step="1" required>
                    <p class="mt-1 text-sm text-gray-500">Quantité actuelle en millilitres</p>
                </div>

                <!-- Seuil minimum -->
                <div class="mb-6">
                    <label for="minimum_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                        Seuil Minimum (ml) *
                    </label>
                    <input type="number" name="minimum_threshold" id="minimum_threshold"
                           value="{{ old('minimum_threshold', $stock->minimum_threshold ?? 1000) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                           min="0" step="1" required>
                    <p class="mt-1 text-sm text-gray-500">Seuil d'alerte pour stock faible</p>
                </div>

                <!-- Capacité maximale -->
                <div class="mb-6">
                    <label for="maximum_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                        Capacité Maximale (ml) *
                    </label>
                    <input type="number" name="maximum_capacity" id="maximum_capacity"
                           value="{{ old('maximum_capacity', $stock->maximum_capacity ?? 10000) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                           min="0" step="1" required>
                    <p class="mt-1 text-sm text-gray-500">Capacité maximale de stockage</p>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                              placeholder="Informations supplémentaires...">{{ old('notes', $stock->notes ?? '') }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('stocks.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ isset($stock) ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
