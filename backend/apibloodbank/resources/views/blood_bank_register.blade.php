@extends('layouts.app')

@section('title', 'Enregistrer une banque de sang - BloodLink')
@section('description', 'Formulaire d'enregistrement d'une nouvelle banque de sang sur BloodLink.')

@section('content')
<div class="bg-white py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold text-red-700 mb-6">Enregistrer une banque de sang</h1>
        <p class="text-gray-700 mb-8">Remplissez ce formulaire pour proposer une nouvelle banque de sang. Un administrateur validera votre demande.</p>
        <form method="POST" action="{{ route('blood-bank.register.submit') }}" class="space-y-8 bg-gray-50 p-6 rounded-lg shadow">
            @csrf
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations sur la banque de sang</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Nom de la banque *</label>
                    <input type="text" name="bank_name" id="bank_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_name') }}">
                </div>
                <div>
                    <label for="bank_phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                    <input type="text" name="bank_phone" id="bank_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_phone') }}">
                </div>
                <div>
                    <label for="bank_email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="bank_email" id="bank_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_email') }}">
                </div>
                <div>
                    <label for="bank_website" class="block text-sm font-medium text-gray-700">Site web</label>
                    <input type="url" name="bank_website" id="bank_website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('bank_website') }}">
                </div>
                <div class="md:col-span-2">
                    <label for="bank_address" class="block text-sm font-medium text-gray-700">Adresse *</label>
                    <input type="text" name="bank_address" id="bank_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_address') }}">
                </div>
                <div>
                    <label for="bank_city" class="block text-sm font-medium text-gray-700">Ville *</label>
                    <input type="text" name="bank_city" id="bank_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_city') }}">
                </div>
                <div>
                    <label for="bank_postal_code" class="block text-sm font-medium text-gray-700">Code postal *</label>
                    <input type="text" name="bank_postal_code" id="bank_postal_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('bank_postal_code') }}">
                </div>
                <div>
                    <label for="bank_country" class="block text-sm font-medium text-gray-700">Pays</label>
                    <input type="text" name="bank_country" id="bank_country" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('bank_country', 'France') }}">
                </div>
                <div>
                    <label for="bank_partnership_level" class="block text-sm font-medium text-gray-700">Type de structure *</label>
                    <select name="bank_partnership_level" id="bank_partnership_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required>
                        <option value="">Sélectionner</option>
                        @foreach($partnershipLevels as $key => $label)
                            <option value="{{ $key }}" @if(old('bank_partnership_level') == $key) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="bank_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="bank_description" id="bank_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('bank_description') }}</textarea>
                </div>
                <div>
                    <label for="bank_latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="text" name="bank_latitude" id="bank_latitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('bank_latitude') }}">
                </div>
                <div>
                    <label for="bank_longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="text" name="bank_longitude" id="bank_longitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" value="{{ old('bank_longitude') }}">
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 mb-4 mt-8">Administrateur de la banque</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="admin_first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input type="text" name="admin_first_name" id="admin_first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('admin_first_name') }}">
                </div>
                <div>
                    <label for="admin_last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" name="admin_last_name" id="admin_last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('admin_last_name') }}">
                </div>
                <div>
                    <label for="admin_email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="admin_email" id="admin_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('admin_email') }}">
                </div>
                <div>
                    <label for="admin_phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                    <input type="text" name="admin_phone" id="admin_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required value="{{ old('admin_phone') }}">
                </div>
                <div>
                    <label for="admin_password" class="block text-sm font-medium text-gray-700">Mot de passe *</label>
                    <input type="password" name="admin_password" id="admin_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required>
                </div>
                <div>
                    <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation du mot de passe *</label>
                    <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500" required>
                </div>
            </div>
            <div>
                <button type="submit" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Envoyer la demande
                </button>
            </div>
        </form>
    </div>
</div>
@endsection