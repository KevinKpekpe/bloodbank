<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BloodLink - Don de Sang')</title>
    <meta name="description" content="@yield('description', 'Plateforme de don de sang et gestion des banques de sang')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">BloodLink</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-300 hover:text-red-700 transition-colors">
                            Accueil
                        </a>
                        <a href="{{ route('about') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-300 transition-colors">
                            À propos
                        </a>
                        <a href="{{ route('blood-banks') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-300 transition-colors">
                            Banques de Sang
                        </a>
                        <a href="{{ route('donate') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-300 transition-colors">
                            Faire un Don
                        </a>
                        <a href="{{ route('contact') }}" class="text-gray-500 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-300 transition-colors">
                            Contact
                        </a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <!-- Notifications -->
                        <div class="ml-3 relative">
                            <a href="{{ route('notifications.index') }}" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 006.03 3h11.94a2 2 0 011.84 1.19L21 7v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 01.19-2.81z" />
                                </svg>
                                @php
                                    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())->whereNull('read_at')->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-red-600">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                </div>

                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Tableau de bord
                                        </a>
                                        <a href="{{ route('donations.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Mes dons
                                        </a>
                                        @if(Auth::user()->role && (Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'blood_bank'))
                                            <a href="{{ route('stocks.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Gestion des stocks
                                            </a>
                                        @endif
                                        @if(Auth::user()->role && Auth::user()->role->name === 'admin')
                                            <a href="{{ route('blood-requests.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Demandes de sang
                                            </a>
                                        @endif
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Déconnexion
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Inscription
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold">BloodLink</span>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Plateforme de don de sang connectant donneurs, banques de sang et hôpitaux pour sauver des vies.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens Rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors">À propos</a></li>
                        <li><a href="{{ route('blood-banks') }}" class="text-gray-400 hover:text-white transition-colors">Banques de Sang</a></li>
                        <li><a href="{{ route('donate') }}" class="text-gray-400 hover:text-white transition-colors">Faire un Don</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400">
                            <i class="fas fa-phone mr-2"></i>
                            +33 1 23 45 67 89
                        </li>
                        <li class="text-gray-400">
                            <i class="fas fa-envelope mr-2"></i>
                            contact@bloodlink.fr
                        </li>
                        <li class="text-gray-400">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Paris, France
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} BloodLink. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
