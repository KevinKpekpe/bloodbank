@extends('layouts.app')

@section('title', 'Prendre un rendez-vous - BloodLink')
@section('description', 'Prenez un rendez-vous pour faire un don de sang.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Prendre un rendez-vous</h1>
            <p class="mt-2 text-gray-600">Planifiez votre don de sang et sauvez des vies</p>
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

        @if(!$eligible)
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 mb-2">Vous n'êtes pas éligible pour faire un don</h3>
                        <ul class="text-sm text-red-700 space-y-1">
                            @foreach($eligibilityReasons as $reason)
                                <li>• {{ $reason }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-red-600 hover:text-red-500">
                                Retour au tableau de bord
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($eligible)
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('donations.book.submit') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="blood_bank_id" class="block text-sm font-medium text-gray-700 mb-2">Banque de sang *</label>
                        <select name="blood_bank_id" id="blood_bank_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors" required>
                            <option value="">Sélectionner une banque</option>
                            @foreach($bloodBanks as $bank)
                                <option value="{{ $bank->id }}" @if(old('blood_bank_id') == $bank->id) selected @endif>
                                    {{ $bank->name }} - {{ $bank->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="donation_type" class="block text-sm font-medium text-gray-700 mb-2">Type de don *</label>
                        <select name="donation_type" id="donation_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors" required>
                            <option value="">Sélectionner</option>
                            <option value="whole_blood" @if(old('donation_type') == 'whole_blood') selected @endif>Sang total</option>
                            <option value="plasma" @if(old('donation_type') == 'plasma') selected @endif>Plasma</option>
                            <option value="platelets" @if(old('donation_type') == 'platelets') selected @endif>Plaquettes</option>
                        </select>
                    </div>

                    <div>
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">Date préférée *</label>
                        <input type="date" name="preferred_date" id="preferred_date"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors"
                               required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               value="{{ old('preferred_date') }}">
                    </div>

                    <div>
                        <label for="preferred_time" class="block text-sm font-medium text-gray-700 mb-2">Heure préférée *</label>
                        <select name="preferred_time" id="preferred_time" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors" required>
                            <option value="">Sélectionner</option>
                            <option value="09:00" @if(old('preferred_time') == '09:00') selected @endif>09:00</option>
                            <option value="10:00" @if(old('preferred_time') == '10:00') selected @endif>10:00</option>
                            <option value="11:00" @if(old('preferred_time') == '11:00') selected @endif>11:00</option>
                            <option value="14:00" @if(old('preferred_time') == '14:00') selected @endif>14:00</option>
                            <option value="15:00" @if(old('preferred_time') == '15:00') selected @endif>15:00</option>
                            <option value="16:00" @if(old('preferred_time') == '16:00') selected @endif>16:00</option>
                            <option value="17:00" @if(old('preferred_time') == '17:00') selected @endif>17:00</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 transition-colors"
                              placeholder="Informations supplémentaires...">{{ old('notes') }}</textarea>
                </div>

                <!-- Informations importantes -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Informations importantes</h3>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Assurez-vous d'être en bonne santé le jour du don</li>
                                <li>• Mangez un repas léger avant le don</li>
                                <li>• Buvez beaucoup d'eau la veille et le jour du don</li>
                                <li>• Apportez une pièce d'identité</li>
                                <li>• Prévoyez environ 1 heure pour l'ensemble du processus</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="confirm" name="confirm" type="checkbox" required
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="confirm" class="text-gray-900">
                            Je confirme que je suis éligible pour faire un don de sang et que les informations fournies sont exactes
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6">
                    <a href="{{ route('donations.index') }}"
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg text-sm font-medium transition-colors">
                        Confirmer le rendez-vous
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
