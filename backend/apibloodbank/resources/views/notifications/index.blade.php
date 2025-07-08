@extends('layouts.app')

@section('title', 'Notifications - BloodLink')
@section('description', 'Gérez vos notifications.')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="mt-2 text-gray-600">Restez informé de vos activités et des mises à jour importantes</p>
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
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-semibold text-gray-900">Vos Notifications</h2>
                @if($unreadCount > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $unreadCount }} non lue(s)
                    </span>
                @endif
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        <!-- Liste des notifications -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($notifications->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($notifications as $notification)
                        <li class="px-6 py-4 hover:bg-gray-50 transition-colors {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        @if(!$notification->read_at)
                                            <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                        @endif
                                        <h3 class="text-sm font-medium text-gray-900">
                                            {{ $notification->title }}
                                        </h3>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $notification->message }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="px-6 py-4 bg-gray-50">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 006.03 3h11.94a2 2 0 011.84 1.19L21 7v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 01.19-2.81z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
                    <p class="text-gray-500">Vous n'avez aucune notification pour le moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
