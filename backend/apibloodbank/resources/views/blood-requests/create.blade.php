@extends('layouts.app')

@section('title', 'Nouvelle Demande de Sang - BloodLink')
@section('description', 'Créez une nouvelle demande de sang pour un patient.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle Demande de Sang</h1>
            <p class="mt-2 text-gray-600">Créez une demande de sang pour un patient</p>
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
            <form method="POST" action="{{ route('blood-requests.store') }}">
                @csrf

                <!-- Patient -->
                <div class="mb-6">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Patient *
                    </label>
                    <select name="patient_id" id="patient_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionnez un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }} - {{ $patient->hospital_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type de sang -->
                <div class="mb-6">
                    <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de Sang Requis *
                    </label>
                    <select name="blood_type_id" id="blood_type_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionnez un type de sang</option>
                        @foreach($bloodTypes as $type)
                            <option value="{{ $type->id }}" {{ old('blood_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantité -->
                <div class="mb-6">
                    <label for="quantity_ml" class="block text-sm font-medium text-gray-700 mb-2">
                        Quantité Requise (ml) *
                    </label>
                    <input type="number" name="quantity_ml" id="quantity_ml"
                           value="{{ old('quantity_ml', 450) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                           min="100" max="5000" step="50" required>
                    <p class="mt-1 text-sm text-gray-500">Quantité en millilitres (100ml à 5000ml)</p>
                </div>

                <!-- Niveau d'urgence -->
                <div class="mb-6">
                    <label for="urgency_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Niveau d'Urgence *
                    </label>
                    <select name="urgency_level" id="urgency_level" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionnez le niveau d'urgence</option>
                        <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Faible - Planifié</option>
                        <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>Moyenne - Dans les 24h</option>
                        <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>Élevée - Dans les 6h</option>
                        <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>Critique - Immédiat</option>
                    </select>
                </div>

                <!-- Raison -->
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Raison de la Demande *
                    </label>
                    <textarea name="reason" id="reason" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                              placeholder="Décrivez la raison de la demande de sang..."
                              required>{{ old('reason') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Ex: Chirurgie cardiaque, accident de la route, etc.</p>
                </div>

                <!-- Date requise -->
                <div class="mb-6">
                    <label for="required_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date Requise *
                    </label>
                    <input type="date" name="required_date" id="required_date"
                           value="{{ old('required_date', date('Y-m-d', strtotime('+1 day'))) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    <p class="mt-1 text-sm text-gray-500">Date à laquelle le sang est nécessaire</p>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes Additionnelles
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                              placeholder="Informations supplémentaires, contraintes spéciales...">{{ old('notes') }}</textarea>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('blood-requests.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                        Créer la Demande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
