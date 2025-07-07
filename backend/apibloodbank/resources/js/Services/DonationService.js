import axios from 'axios'

class DonationService {
  constructor() {
    this.baseURL = '/api/donations'
  }

  // Récupérer l'historique des dons de l'utilisateur connecté
  async getHistory() {
    try {
      const response = await axios.get(`${this.baseURL}/history`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération de l\'historique:', error)
      throw error
    }
  }

  // Prendre un rendez-vous de don
  async bookAppointment(appointmentData) {
    try {
      const response = await axios.post(`${this.baseURL}/appointments`, appointmentData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la réservation:', error)
      throw error
    }
  }

  // Annuler un rendez-vous
  async cancelAppointment(appointmentId) {
    try {
      const response = await axios.delete(`${this.baseURL}/appointments/${appointmentId}`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de l\'annulation:', error)
      throw error
    }
  }

  // Modifier un rendez-vous
  async updateAppointment(appointmentId, appointmentData) {
    try {
      const response = await axios.put(`${this.baseURL}/appointments/${appointmentId}`, appointmentData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la modification:', error)
      throw error
    }
  }

  // Récupérer les statistiques du donneur
  async getDonorStats() {
    try {
      const response = await axios.get(`${this.baseURL}/stats`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des statistiques:', error)
      throw error
    }
  }

  // Vérifier l'éligibilité du donneur
  async checkEligibility() {
    try {
      const response = await axios.get(`${this.baseURL}/eligibility`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la vérification d\'éligibilité:', error)
      throw error
    }
  }

  // Récupérer les disponibilités d'une banque de sang
  async getAvailability(bloodBankId, date) {
    try {
      const response = await axios.get(`${this.baseURL}/availability`, {
        params: {
          blood_bank_id: bloodBankId,
          date: date
        }
      })
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des disponibilités:', error)
      throw error
    }
  }

  // Récupérer les types de don disponibles
  async getDonationTypes() {
    try {
      const response = await axios.get(`${this.baseURL}/types`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des types de don:', error)
      throw error
    }
  }

  // Marquer un don comme complété (pour le personnel médical)
  async completeDonation(donationId, completionData) {
    try {
      const response = await axios.post(`${this.baseURL}/${donationId}/complete`, completionData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la finalisation du don:', error)
      throw error
    }
  }

  // Récupérer les détails d'un don spécifique
  async getDonationDetails(donationId) {
    try {
      const response = await axios.get(`${this.baseURL}/${donationId}`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des détails:', error)
      throw error
    }
  }

  // Envoyer un rappel de rendez-vous
  async sendReminder(appointmentId) {
    try {
      const response = await axios.post(`${this.baseURL}/appointments/${appointmentId}/reminder`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de l\'envoi du rappel:', error)
      throw error
    }
  }

  // Récupérer les critères d'éligibilité détaillés
  async getEligibilityCriteria() {
    try {
      const response = await axios.get(`${this.baseURL}/eligibility-criteria`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des critères:', error)
      throw error
    }
  }

  // Soumettre un questionnaire de santé pré-don
  async submitHealthQuestionnaire(donationId, questionnaireData) {
    try {
      const response = await axios.post(`${this.baseURL}/${donationId}/questionnaire`, questionnaireData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la soumission du questionnaire:', error)
      throw error
    }
  }

  // Récupérer les instructions post-don
  async getPostDonationInstructions(donationType) {
    try {
      const response = await axios.get(`${this.baseURL}/post-donation-instructions`, {
        params: { type: donationType }
      })
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des instructions:', error)
      throw error
    }
  }

  // Générer un certificat de don
  async generateDonationCertificate(donationId) {
    try {
      const response = await axios.get(`${this.baseURL}/${donationId}/certificate`, {
        responseType: 'blob'
      })
      return response.data
    } catch (error) {
      console.error('Erreur lors de la génération du certificat:', error)
      throw error
    }
  }

  // Récupérer les notifications liées aux dons
  async getDonationNotifications() {
    try {
      const response = await axios.get(`${this.baseURL}/notifications`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des notifications:', error)
      throw error
    }
  }

  // Marquer une notification comme lue
  async markNotificationAsRead(notificationId) {
    try {
      const response = await axios.put(`/api/notifications/${notificationId}/read`)
      return response.data
    } catch (error) {
      console.error('Erreur lors du marquage de la notification:', error)
      throw error
    }
  }
}

export default new DonationService()
