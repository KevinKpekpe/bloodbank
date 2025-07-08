@extends('layouts.app')

@section('title', 'Tableau de bord - BloodLink')
@section('description', 'Votre tableau de bord personnel BloodLink.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Tableau de bord</h1>
            <p class="mt-2 text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
        </div>

        <!-- User info card -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                    <span class="text-xl font-medium text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}{{ substr(strstr(Auth::user()->name, ' '), 1, 1) ?? '' }}
                    </span>
                </div>
                <div class="ml-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h2>
                    <p class="text-gray-600">{{ Auth::user()->email }}</p>
                    <p class="text-sm text-gray-500">
                        Rôle: {{ Auth::user()->role->name ?? 'Non défini' }}
                    </p>
                    @if(Auth::user()->bloodType)
                        <p class="text-sm text-gray-500">
                            Groupe sanguin: {{ Auth::user()->bloodType->name }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Dons effectués
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $stats['donations'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Vies sauvées
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $stats['livesSaved'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Prochain don
                            </dt>
                            <dd class="text-2xl font-bold text-gray-900">
                                {{ $stats['nextDonation'] ?? 'Non planifié' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick actions -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Actions rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('blood-banks') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-red-300 transition-all duration-200">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Trouver une banque</span>
                </a>

                <a href="{{ route('donations.book') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-red-300 transition-all duration-200">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.171a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Faire un don</span>
                </a>

                <a href="{{ route('donations.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-red-300 transition-all duration-200">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Mes dons</span>
                </a>

                <a href="{{ route('contact') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-red-300 transition-all duration-200">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Contact</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
