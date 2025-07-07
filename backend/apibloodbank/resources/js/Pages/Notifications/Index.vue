<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
              <p class="mt-2 text-gray-600">Gérez vos notifications et rappels</p>
            </div>
            <div class="flex items-center space-x-4">
              <button
                @click="markAllAsRead"
                :disabled="unreadCount === 0"
                class="bg-red-600 hover:bg-red-700 disabled:bg-gray-300 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
              >
                Tout marquer comme lu
              </button>
              <button
                @click="refreshNotifications"
                :disabled="loading"
                class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors"
              >
                <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Actualiser
              </button>
            </div>
          </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.total }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Non lues</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.unread }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Lues</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.read }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Aujourd'hui</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.today }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-lg shadow mb-8">
          <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <!-- Recherche -->
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Rechercher dans les notifications..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                />
              </div>

              <!-- Filtre par type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select
                  v-model="typeFilter"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                >
                  <option value="">Tous les types</option>
                  <option value="appointment">Rendez-vous</option>
                  <option value="reminder">Rappel</option>
                  <option value="donation_completed">Don complété</option>
                  <option value="system">Système</option>
                </select>
              </div>

              <!-- Filtre par statut -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select
                  v-model="statusFilter"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                >
                  <option value="">Tous</option>
                  <option value="unread">Non lues</option>
                  <option value="read">Lues</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Liste des notifications -->
          <div class="divide-y divide-gray-200">
            <!-- État de chargement -->
            <div v-if="loading" class="p-8 text-center">
              <svg class="animate-spin w-8 h-8 mx-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <p class="mt-2 text-gray-500">Chargement des notifications...</p>
            </div>

            <!-- État vide -->
            <div v-else-if="filteredNotifications.length === 0" class="p-8 text-center">
              <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A4 4 0 014 6v6a4 4 0 01-4 4h2a2 2 0 002 2h8a2 2 0 002-2h2a4 4 0 004-4V6a4 4 0 00-4-4H6a4 4 0 00-2.81 1.19z"/>
              </svg>
              <p class="mt-4 text-gray-500">Aucune notification trouvée</p>
            </div>

            <!-- Notifications -->
            <div
              v-for="notification in filteredNotifications"
              :key="notification.id"
              :class="[
                'p-6 hover:bg-gray-50 transition-colors',
                !notification.read_at ? 'bg-blue-50' : ''
              ]"
            >
              <div class="flex items-start">
                <!-- Icône -->
                <div class="flex-shrink-0 mr-4">
                  <div :class="getNotificationIconClass(notification.type)" class="w-10 h-10 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                      <path v-if="notification.type === 'appointment'" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                      <path v-else-if="notification.type === 'reminder'" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                      <path v-else-if="notification.type === 'donation_completed'" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      <path v-else d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                  </div>
                </div>

                <!-- Contenu -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">
                      {{ notification.title }}
                    </h3>
                    <div class="flex items-center space-x-2">
                      <span :class="getTypeBadgeClass(notification.type)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ getTypeLabel(notification.type) }}
                      </span>
                      <span v-if="!notification.read_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Non lu
                      </span>
                    </div>
                  </div>
                  <p class="mt-2 text-gray-600">
                    {{ notification.message }}
                  </p>
                  <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                      {{ formatDate(notification.created_at) }}
                    </p>
                    <div class="flex items-center space-x-2">
                      <button
                        v-if="!notification.read_at"
                        @click="markAsRead(notification.id)"
                        class="text-sm text-red-600 hover:text-red-700 font-medium"
                      >
                        Marquer comme lu
                      </button>
                      <button
                        @click="deleteNotification(notification.id)"
                        class="text-sm text-gray-500 hover:text-red-600 font-medium"
                      >
                        Supprimer
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-700">
                Affichage de {{ (currentPage - 1) * perPage + 1 }} à {{ Math.min(currentPage * perPage, total) }} sur {{ total }} notifications
              </div>
              <div class="flex items-center space-x-2">
                <button
                  @click="changePage(currentPage - 1)"
                  :disabled="currentPage === 1"
                  class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Précédent
                </button>
                <span class="px-3 py-2 text-sm text-gray-700">
                  Page {{ currentPage }} sur {{ totalPages }}
                </span>
                <button
                  @click="changePage(currentPage + 1)"
                  :disabled="currentPage === totalPages"
                  class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import NotificationService from '@/Services/NotificationService'

export default {
  name: 'NotificationsIndex',
  components: {
    AppLayout
  },
  setup() {
    const loading = ref(false)
    const notifications = ref([])
    const searchQuery = ref('')
    const typeFilter = ref('')
    const statusFilter = ref('')
    const currentPage = ref(1)
    const perPage = ref(20)
    const total = ref(0)
    const unreadCount = ref(0)

    const stats = ref({
      total: 0,
      unread: 0,
      read: 0,
      today: 0
    })

    // Computed properties
    const filteredNotifications = computed(() => {
      let filtered = notifications.value

      // Filtre par recherche
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase()
        filtered = filtered.filter(n =>
          n.title.toLowerCase().includes(query) ||
          n.message.toLowerCase().includes(query)
        )
      }

      // Filtre par type
      if (typeFilter.value) {
        filtered = filtered.filter(n => n.type === typeFilter.value)
      }

      // Filtre par statut
      if (statusFilter.value === 'unread') {
        filtered = filtered.filter(n => !n.read_at)
      } else if (statusFilter.value === 'read') {
        filtered = filtered.filter(n => n.read_at)
      }

      return filtered
    })

    const totalPages = computed(() => Math.ceil(total.value / perPage.value))

    // Methods
    const loadNotifications = async () => {
      loading.value = true
      try {
        const response = await NotificationService.getNotifications({
          page: currentPage.value,
          per_page: perPage.value
        })
        notifications.value = response.data.data || []
        total.value = response.data.total || 0
        updateStats()
      } catch (error) {
        console.error('Erreur lors du chargement des notifications:', error)
      } finally {
        loading.value = false
      }
    }

    const loadUnreadCount = async () => {
      try {
        unreadCount.value = await NotificationService.getUnreadCount()
      } catch (error) {
        console.error('Erreur lors du chargement du compteur:', error)
      }
    }

    const updateStats = () => {
      const today = new Date().toISOString().split('T')[0]

      stats.value = {
        total: notifications.value.length,
        unread: notifications.value.filter(n => !n.read_at).length,
        read: notifications.value.filter(n => n.read_at).length,
        today: notifications.value.filter(n => n.created_at.startsWith(today)).length
      }
    }

    const markAsRead = async (notificationId) => {
      try {
        await NotificationService.markAsRead(notificationId)
        const notification = notifications.value.find(n => n.id === notificationId)
        if (notification) {
          notification.read_at = new Date().toISOString()
          updateStats()
          loadUnreadCount()
        }
      } catch (error) {
        console.error('Erreur lors du marquage comme lu:', error)
      }
    }

    const markAllAsRead = async () => {
      try {
        await NotificationService.markAllAsRead()
        notifications.value.forEach(n => n.read_at = new Date().toISOString())
        updateStats()
        loadUnreadCount()
      } catch (error) {
        console.error('Erreur lors du marquage global:', error)
      }
    }

    const deleteNotification = async (notificationId) => {
      if (!confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')) return

      try {
        await NotificationService.deleteNotification(notificationId)
        notifications.value = notifications.value.filter(n => n.id !== notificationId)
        updateStats()
        loadUnreadCount()
      } catch (error) {
        console.error('Erreur lors de la suppression:', error)
      }
    }

    const refreshNotifications = () => {
      loadNotifications()
      loadUnreadCount()
    }

    const changePage = (page) => {
      currentPage.value = page
      loadNotifications()
    }

    const getNotificationIconClass = (type) => {
      return NotificationService.getIconClass(type)
    }

    const getTypeBadgeClass = (type) => {
      return NotificationService.getBadgeClass(type)
    }

    const getTypeLabel = (type) => {
      return NotificationService.getTypeLabel(type)
    }

    const formatDate = (dateString) => {
      return NotificationService.formatTimeAgo(dateString)
    }

    // Watchers
    watch([searchQuery, typeFilter, statusFilter], () => {
      currentPage.value = 1
    })

    // Lifecycle
    onMounted(() => {
      loadNotifications()
      loadUnreadCount()
    })

    return {
      loading,
      notifications,
      searchQuery,
      typeFilter,
      statusFilter,
      currentPage,
      perPage,
      total,
      unreadCount,
      stats,
      filteredNotifications,
      totalPages,
      loadNotifications,
      markAsRead,
      markAllAsRead,
      deleteNotification,
      refreshNotifications,
      changePage,
      getNotificationIconClass,
      getTypeBadgeClass,
      getTypeLabel,
      formatDate
    }
  }
}
</script>
