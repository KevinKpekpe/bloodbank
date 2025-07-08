@extends('layouts.app')

@section('title', 'Inscription - BloodLink')
@section('description', 'Créez votre compte BloodLink pour participer aux dons de sang et sauver des vies.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer votre compte</h1>
            <p class="text-gray-600">Rejoignez BloodLink et commencez à sauver des vies</p>
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

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
                        <input type="text" name="name" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" required value="{{ old('name') }}">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email *</label>
                        <input type="email" name="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" required value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                        <input type="text" name="phone" id="phone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" value="{{ old('phone') }}">
                    </div>
                    <div>
                        <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">Groupe sanguin</label>
                        <select name="blood_type_id" id="blood_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                            <option value="">Sélectionner</option>
                            @foreach(\App\Models\BloodType::all() as $bloodType)
                                <option value="{{ $bloodType->id }}" @if(old('blood_type_id') == $bloodType->id) selected @endif>{{ $bloodType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" value="{{ old('date_of_birth') }}">
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Genre</label>
                        <select name="gender" id="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors">
                            <option value="">Sélectionner</option>
                            <option value="male" @if(old('gender') == 'male') selected @endif>Homme</option>
                            <option value="female" @if(old('gender') == 'female') selected @endif>Femme</option>
                            <option value="other" @if(old('gender') == 'other') selected @endif>Autre</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <input type="text" name="address" id="address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" value="{{ old('address') }}">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                        <input type="text" name="city" id="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" value="{{ old('city') }}">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                        <input type="text" name="postal_code" id="postal_code" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" value="{{ old('postal_code') }}">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rôle *</label>
                        <select name="role" id="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" required>
                            <option value="">Sélectionner</option>
                            <option value="donor" @if(old('role') == 'donor') selected @endif>Donneur</option>
                            <option value="doctor" @if(old('role') == 'doctor') selected @endif>Médecin</option>
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmation du mot de passe *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors" required>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="text-gray-900">
                            J'accepte les <a href="#" class="text-red-600 hover:text-red-500 transition-colors">conditions d'utilisation</a> et la <a href="#" class="text-red-600 hover:text-red-500 transition-colors">politique de confidentialité</a>
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Créer mon compte
                    </button>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Déjà un compte ?
                        <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500 transition-colors">
                            Connectez-vous
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
