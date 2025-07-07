import { createRouter, createWebHistory } from 'vue-router'
import AuthService from '@/Services/AuthService'

// Import des pages
import Home from '@/Pages/Home.vue'
import About from '@/Pages/About.vue'
import Contact from '@/Pages/Contact.vue'
import Donate from '@/Pages/Donate.vue'
import BloodBanks from '@/Pages/BloodBanks.vue'
import BloodBankRegister from '@/Pages/BloodBank/Register.vue'
import Login from '@/Pages/Auth/Login.vue'
import Register from '@/Pages/Auth/Register.vue'
import ForgotPassword from '@/Pages/Auth/ForgotPassword.vue'
import Dashboard from '@/Pages/Dashboard/Index.vue'
import DonationIndex from '@/Pages/Donation/Index.vue'
import NotificationsIndex from '@/Pages/Notifications/Index.vue'

const routes = [
    // Routes publiques
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/about',
        name: 'about',
        component: About
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact
    },
    {
        path: '/donate',
        name: 'donate',
        component: Donate
    },
    {
        path: '/blood-banks',
        name: 'blood-banks',
        component: BloodBanks
    },
    {
        path: '/blood-bank/register',
        name: 'blood-bank-register',
        component: BloodBankRegister
    },

    // Routes d'authentification
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { requiresGuest: true }
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: ForgotPassword,
        meta: { requiresGuest: true }
    },

    // Routes protégées
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/donations',
        name: 'donations',
        component: DonationIndex,
        meta: { requiresAuth: true }
    },
    {
        path: '/notifications',
        name: 'notifications',
        component: NotificationsIndex,
        meta: { requiresAuth: true }
    },

    // Route 404
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('@/Pages/NotFound.vue')
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
    const isAuthenticated = AuthService.isAuthenticated()

    // Routes qui nécessitent une authentification
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login')
        return
    }

    // Routes qui nécessitent d'être invité (non connecté)
    if (to.meta.requiresGuest && isAuthenticated) {
        next('/dashboard')
        return
    }

    next()
})

export default router
