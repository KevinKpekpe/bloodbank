import axios from 'axios'

const API_BASE_URL = '/api'

// Configuration axios avec intercepteur pour le token
const apiClient = axios.create({
    baseURL: API_BASE_URL,
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

// Intercepteur pour gérer les erreurs d'authentification
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Ne pas rediriger automatiquement, laisser chaque composant gérer l'erreur
            // localStorage.removeItem('auth_token')
            // localStorage.removeItem('user')
            // window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)

class AuthService {
    /**
     * Connexion utilisateur
     */
    async login(credentials) {
        try {
            const response = await apiClient.post('/auth/login', credentials)

            if (response.data.token) {
                localStorage.setItem('auth_token', response.data.token)
                localStorage.setItem('user', JSON.stringify(response.data.user))
            }

            return response.data
        } catch (error) {
            throw this.handleError(error)
        }
    }

    /**
     * Inscription utilisateur
     */
    async register(userData) {
        try {
            const response = await apiClient.post('/auth/register', userData)

            if (response.data.token) {
                localStorage.setItem('auth_token', response.data.token)
                localStorage.setItem('user', JSON.stringify(response.data.user))
            }

            return response.data
        } catch (error) {
            throw this.handleError(error)
        }
    }

    /**
     * Déconnexion utilisateur
     */
    async logout() {
        try {
            await apiClient.post('/auth/logout')
        } catch (error) {
            console.error('Erreur lors de la déconnexion:', error)
        } finally {
            localStorage.removeItem('auth_token')
            localStorage.removeItem('user')
        }
    }

    /**
     * Récupérer le profil utilisateur
     */
    async getProfile() {
        try {
            const response = await apiClient.get('/auth/me')
            return response.data
        } catch (error) {
            throw this.handleError(error)
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    isAuthenticated() {
        return !!localStorage.getItem('auth_token')
    }

    /**
     * Récupérer l'utilisateur stocké
     */
    getCurrentUser() {
        const user = localStorage.getItem('user')
        return user ? JSON.parse(user) : null
    }

    /**
     * Récupérer le token d'authentification
     */
    getToken() {
        return localStorage.getItem('auth_token')
    }

    /**
     * Gestion des erreurs
     */
    handleError(error) {
        if (error.response?.data?.errors) {
            return {
                type: 'validation',
                errors: error.response.data.errors
            }
        }

        if (error.response?.data?.message) {
            return {
                type: 'message',
                message: error.response.data.message
            }
        }

        return {
            type: 'error',
            message: 'Une erreur inattendue s\'est produite'
        }
    }
}

export default new AuthService()