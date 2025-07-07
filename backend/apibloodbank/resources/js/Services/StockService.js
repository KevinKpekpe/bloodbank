import axios from 'axios'

class StockService {
  constructor() {
    this.client = axios.create({
      baseURL: '/api',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      }
    })

    // Intercepteur pour ajouter le token d'authentification
    this.client.interceptors.request.use(config => {
      const token = localStorage.getItem('auth_token')
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
      return config
    })
  }

  // Récupérer tous les stocks d'une banque
  async getStocks(bankId) {
    try {
      const response = await this.client.get(`/blood-banks/${bankId}/stocks`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération des stocks:', error)
      throw error
    }
  }

  // Mettre à jour un stock
  async update(stockId, stockData) {
    try {
      const response = await this.client.put(`/stocks/${stockId}`, stockData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la mise à jour du stock:', error)
      throw error
    }
  }

  // Créer un nouveau stock
  async create(stockData) {
    try {
      const response = await this.client.post('/stocks', stockData)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la création du stock:', error)
      throw error
    }
  }

  // Supprimer un stock
  async delete(stockId) {
    try {
      const response = await this.client.delete(`/stocks/${stockId}`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la suppression du stock:', error)
      throw error
    }
  }

  // Récupérer l'historique des modifications d'un stock
  async getHistory(stockId) {
    try {
      const response = await this.client.get(`/stocks/${stockId}/history`)
      return response.data
    } catch (error) {
      console.error('Erreur lors de la récupération de l\'historique:', error)
      throw error
    }
  }
}

export default new StockService()
