import axios from 'axios'

// Configuration axios avec intercepteur pour le token
const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    }
})

// Intercepteur pour ajouter le token d'authentification
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

class NotificationService {
  constructor() {
    this.baseURL = '/api/notifications'
  }

  // Récupérer les notifications avec pagination
  async getNotifications(params = {}) {
    try {
      const response = await apiClient.get('/notifications', { params })
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des notifications:', error)
      throw error
    }
  }

  // Récupérer le nombre de notifications non lues
  async getUnreadCount() {
    try {
      const response = await apiClient.get('/notifications/unread-count')
      return response.data.count
    } catch (error) {
      console.error('Erreur lors de la récupération du compteur:', error)
      throw error
    }
  }

  // Marquer une notification comme lue
  async markAsRead(notificationId) {
    try {
      const response = await apiClient.patch(`/notifications/${notificationId}/read`)
      return response.data
    } catch (error) {
      console.error('Erreur lors du marquage comme lu:', error)
      throw error
    }
  }

  // Marquer toutes les notifications comme lues
  async markAllAsRead() {
    try {
      const response = await apiClient.patch('/notifications/mark-all-read')
      return response.data
    } catch (error) {
      console.error('Erreur lors du marquage global:', error)
      throw error
    }
  }

  // Supprimer une notification
  async deleteNotification(notificationId) {
    try {
      const response = await apiClient.delete(`/notifications/${notificationId}`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la suppression:', error)
      throw error
    }
  }

  // Obtenir les statistiques des notifications
  async getStatistics() {
    try {
      const response = await apiClient.get('/notifications/statistics')
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des statistiques:', error)
      throw error
    }
  }

  // Marquer plusieurs notifications comme lues
  async markMultipleAsRead(notificationIds) {
    try {
      const response = await apiClient.patch('/notifications/mark-multiple-read', {
        notification_ids: notificationIds
      })
      return response.data
    } catch (error) {
      console.error('Erreur lors du marquage multiple:', error)
      throw error
    }
  }

  // Supprimer plusieurs notifications
  async deleteMultiple(notificationIds) {
    try {
      const response = await apiClient.delete('/notifications/delete-multiple', {
        data: { notification_ids: notificationIds }
      })
      return response.data
    } catch (error) {
      console.error('Erreur lors de la suppression multiple:', error)
      throw error
    }
  }

  // Créer une notification (admin seulement)
  async createNotification(notificationData) {
    try {
      const response = await apiClient.post('/notifications', notificationData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la création de la notification:', error)
      throw error
    }
  }

  // Envoyer des notifications en masse (admin seulement)
  async sendBulkNotifications(bulkData) {
    try {
      const response = await apiClient.post('/notifications/bulk', bulkData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de l\'envoi en masse:', error)
      throw error
    }
  }

  // Formater le temps écoulé
  formatTimeAgo(dateString) {
    const date = new Date(dateString)
    const now = new Date()
    const diffInMinutes = Math.floor((now - date) / (1000 * 60))

    if (diffInMinutes < 1) return 'À l\'instant'
    if (diffInMinutes < 60) return `Il y a ${diffInMinutes} min`

    const diffInHours = Math.floor(diffInMinutes / 60)
    if (diffInHours < 24) return `Il y a ${diffInHours}h`

    const diffInDays = Math.floor(diffInHours / 24)
    if (diffInDays < 7) return `Il y a ${diffInDays}j`

    return date.toLocaleDateString('fr-FR')
  }

  // Obtenir le label du type de notification
  getTypeLabel(type) {
    const labels = {
      appointment: 'Rendez-vous',
      reminder: 'Rappel',
      donation_completed: 'Don complété',
      system: 'Système'
    }
    return labels[type] || type
  }

  // Obtenir la classe CSS de l'icône selon le type
  getIconClass(type) {
    const classes = {
      appointment: 'bg-blue-500',
      reminder: 'bg-yellow-500',
      donation_completed: 'bg-green-500',
      system: 'bg-gray-500'
    }
    return classes[type] || 'bg-gray-500'
  }

  // Obtenir la classe CSS du badge selon le type
  getBadgeClass(type) {
    const classes = {
      appointment: 'bg-blue-100 text-blue-800',
      reminder: 'bg-yellow-100 text-yellow-800',
      donation_completed: 'bg-green-100 text-green-800',
      system: 'bg-gray-100 text-gray-800'
    }
    return classes[type] || 'bg-gray-100 text-gray-800'
  }

  // Filtrer les notifications
  filterNotifications(notifications, filters = {}) {
    let filtered = [...notifications]

    // Filtre par recherche
    if (filters.search) {
      const query = filters.search.toLowerCase()
      filtered = filtered.filter(n =>
        n.title.toLowerCase().includes(query) ||
        n.message.toLowerCase().includes(query)
      )
    }

    // Filtre par type
    if (filters.type) {
      filtered = filtered.filter(n => n.type === filters.type)
    }

    // Filtre par statut
    if (filters.status === 'unread') {
      filtered = filtered.filter(n => !n.read_at)
    } else if (filters.status === 'read') {
      filtered = filtered.filter(n => n.read_at)
    }

    return filtered
  }

  // Calculer les statistiques
  calculateStats(notifications) {
    const today = new Date().toISOString().split('T')[0]

    return {
      total: notifications.length,
      unread: notifications.filter(n => !n.read_at).length,
      read: notifications.filter(n => n.read_at).length,
      today: notifications.filter(n => n.created_at.startsWith(today)).length,
      byType: notifications.reduce((acc, n) => {
        acc[n.type] = (acc[n.type] || 0) + 1
        return acc
      }, {})
    }
  }
}

export default new NotificationService()
