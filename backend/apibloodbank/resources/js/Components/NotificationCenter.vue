<template>
  <div class="relative">
    <!-- Bouton de notification -->
    <button
      @click="toggleNotifications"
      class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A4 4 0 014 6v6a4 4 0 01-4 4h2a2 2 0 002 2h8a2 2 0 002-2h2a4 4 0 004-4V6a4 4 0 00-4-4H6a4 4 0 00-2.81 1.19z"/>
      </svg>

      <!-- Badge de notifications non lues -->
      <span
        v-if="unreadCount > 0"
        class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"
      >
        {{ unreadCount > 99 ? '99+' : unreadCount }}
      </span>
    </button>

    <!-- Panneau de notifications -->
    <div
      v-if="showNotifications"
      class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50"
    >
      <div class="p-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
          <button
            @click="markAllAsRead"
            class="text-sm text-red-600 hover:text-red-700"
            v-if="unreadCount > 0"
          >
            Tout marquer comme lu
          </button>
        </div>
      </div>

      <div class="max-h-96 overflow-y-auto">
        <!-- État de chargement -->
        <div v-if="loading" class="p-4 text-center">
          <svg class="animate-spin w-6 h-6 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-2 text-sm text-gray-500">Chargement...</p>
        </div>

        <!-- État vide -->
        <div v-else-if="notifications.length === 0" class="p-4 text-center">
          <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A4 4 0 014 6v6a4 4 0 01-4 4h2a2 2 0 002 2h8a2 2 0 002-2h2a4 4 0 004-4V6a4 4 0 00-4-4H6a4 4 0 00-2.81 1.19z"/>
          </svg>
          <p class="mt-2 text-sm text-gray-500">Aucune notification</p>
        </div>

        <!-- Liste des notifications -->
        <div v-else>
          <div
            v-for="notification in notifications"
            :key="notification.id"
            :class="[
              'p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors',
              !notification.read_at ? 'bg-blue-50' : ''
            ]"
            @click="markAsRead(notification.id)"
          >
            <div class="flex items-start">
              <!-- Icône selon le type -->
              <div class="flex-shrink-0 mr-3">
                <div :class="getNotificationIconClass(notification.type)" class="w-8 h-8 rounded-full flex items-center justify-center">
                  <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path v-if="notification.type === 'appointment'" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                    <path v-else-if="notification.type === 'reminder'" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    <path v-else-if="notification.type === 'donation_completed'" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    <path v-else d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                  </svg>
                </div>
              </div>

              <!-- Contenu -->
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">
                  {{ notification.title }}
                </p>
                <p class="text-sm text-gray-600 mt-1">
                  {{ notification.message }}
                </p>
                <p class="text-xs text-gray-400 mt-2">
                  {{ formatTimeAgo(notification.created_at) }}
                </p>
              </div>

              <!-- Indicateur non lu -->
              <div v-if="!notification.read_at" class="flex-shrink-0 ml-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-4 border-t border-gray-200">
        <router-link
          to="/notifications"
          class="block text-center text-sm text-red-600 hover:text-red-700 font-medium"
        >
          Voir toutes les notifications
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue'
import NotificationService from '@/Services/NotificationService'
import AuthService from '@/Services/AuthService'

export default {
  name: 'NotificationCenter',
  setup() {
    const showNotifications = ref(false)
    const loading = ref(false)
    const notifications = ref([])
    const unreadCount = ref(0)

    // Vérifier si l'utilisateur est authentifié
    const isAuthenticated = () => {
      return AuthService.isAuthenticated()
    }

    // Charger les notifications
    const loadNotifications = async () => {
      if (!isAuthenticated()) return

      loading.value = true
      try {
        const response = await NotificationService.getNotifications()
        notifications.value = response.data || []
        updateUnreadCount()
      } catch (error) {
        if (error.response?.status === 401) {
          // Utilisateur non authentifié, on ne fait rien
          console.log('Utilisateur non authentifié, notifications ignorées')
        } else {
          console.error('Erreur lors du chargement des notifications:', error)
        }
      } finally {
        loading.value = false
      }
    }

    // Charger le nombre de notifications non lues
    const loadUnreadCount = async () => {
      if (!isAuthenticated()) return

      try {
        unreadCount.value = await NotificationService.getUnreadCount()
      } catch (error) {
        if (error.response?.status === 401) {
          // Utilisateur non authentifié, on ne fait rien
          console.log('Utilisateur non authentifié, compteur ignoré')
        } else {
          console.error('Erreur lors du chargement du compteur:', error)
        }
      }
    }

    // Mettre à jour le compteur de notifications non lues
    const updateUnreadCount = () => {
      unreadCount.value = notifications.value.filter(n => !n.read_at).length
    }

    // Marquer une notification comme lue
    const markAsRead = async (notificationId) => {
      if (!isAuthenticated()) return

      try {
        await NotificationService.markAsRead(notificationId)
        const notification = notifications.value.find(n => n.id === notificationId)
        if (notification) {
          notification.read_at = new Date().toISOString()
          updateUnreadCount()
        }
      } catch (error) {
        if (error.response?.status === 401) {
          console.log('Utilisateur non authentifié, marquage ignoré')
        } else {
          console.error('Erreur lors du marquage comme lu:', error)
        }
      }
    }

    // Marquer toutes les notifications comme lues
    const markAllAsRead = async () => {
      if (!isAuthenticated()) return

      try {
        await NotificationService.markAllAsRead()
        notifications.value.forEach(n => n.read_at = new Date().toISOString())
        updateUnreadCount()
      } catch (error) {
        if (error.response?.status === 401) {
          console.log('Utilisateur non authentifié, marquage global ignoré')
        } else {
          console.error('Erreur lors du marquage global:', error)
        }
      }
    }

    // Basculer l'affichage des notifications
    const toggleNotifications = () => {
      showNotifications.value = !showNotifications.value
      if (showNotifications.value && isAuthenticated()) {
        loadNotifications()
      }
    }

    // Obtenir la classe CSS de l'icône selon le type
    const getNotificationIconClass = (type) => {
      return NotificationService.getIconClass(type)
    }

    // Formater le temps écoulé
    const formatTimeAgo = (dateString) => {
      return NotificationService.formatTimeAgo(dateString)
    }

    // Fermer le panneau en cliquant à l'extérieur
    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        showNotifications.value = false
      }
    }

    // Polling pour les nouvelles notifications
    let pollingInterval

    const startPolling = () => {
      pollingInterval = setInterval(() => {
        if (!showNotifications.value && isAuthenticated()) {
          loadUnreadCount()
        }
      }, 30000) // Vérifier toutes les 30 secondes
    }

    const stopPolling = () => {
      if (pollingInterval) {
        clearInterval(pollingInterval)
      }
    }

    onMounted(() => {
      if (isAuthenticated()) {
        loadUnreadCount()
        startPolling()
      }
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      stopPolling()
      document.removeEventListener('click', handleClickOutside)
    })

    return {
      showNotifications,
      loading,
      notifications,
      unreadCount,
      toggleNotifications,
      markAsRead,
      markAllAsRead,
      getNotificationIconClass,
      formatTimeAgo
    }
  }
}
</script>
