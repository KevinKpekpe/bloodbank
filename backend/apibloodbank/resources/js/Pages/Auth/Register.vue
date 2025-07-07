<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Logo -->
            <div class="flex justify-center">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Créer votre compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <router-link to="/login" class="font-medium text-red-600 hover:text-red-500">
                    connectez-vous à votre compte existant
                </router-link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <!-- Message d'erreur général -->
                <div v-if="generalError" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ generalError }}
                </div>

                <!-- Message de succès -->
                <div v-if="successMessage" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ successMessage }}
                </div>

                <form class="space-y-6" @submit.prevent="submit">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nom complet
                        </label>
                        <div class="mt-1">
                            <input
                                id="name"
                                v-model="form.name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                required
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.name ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="Votre nom complet"
                            />
                        </div>
                        <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                            {{ errors.name }}
                        </p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <div class="mt-1">
                            <input
                                id="email"
                                v-model="form.email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.email ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="votre@email.com"
                            />
                        </div>
                        <p v-if="errors.email" class="mt-2 text-sm text-red-600">
                            {{ errors.email }}
                        </p>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Téléphone
                        </label>
                        <div class="mt-1">
                            <input
                                id="phone"
                                v-model="form.phone"
                                name="phone"
                                type="tel"
                                autocomplete="tel"
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.phone ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="06 12 34 56 78"
                            />
                        </div>
                        <p v-if="errors.phone" class="mt-2 text-sm text-red-600">
                            {{ errors.phone }}
                        </p>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Adresse
                        </label>
                        <div class="mt-1">
                            <input
                                id="address"
                                v-model="form.address"
                                name="address"
                                type="text"
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.address ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="123 Rue de la Paix"
                            />
                        </div>
                        <p v-if="errors.address" class="mt-2 text-sm text-red-600">
                            {{ errors.address }}
                        </p>
                    </div>

                    <!-- City -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">
                            Ville
                        </label>
                        <div class="mt-1">
                            <input
                                id="city"
                                v-model="form.city"
                                name="city"
                                type="text"
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.city ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="Paris"
                            />
                        </div>
                        <p v-if="errors.city" class="mt-2 text-sm text-red-600">
                            {{ errors.city }}
                        </p>
                    </div>

                    <!-- Postal Code -->
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">
                            Code postal
                        </label>
                        <div class="mt-1">
                            <input
                                id="postal_code"
                                v-model="form.postal_code"
                                name="postal_code"
                                type="text"
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.postal_code ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="75001"
                            />
                        </div>
                        <p v-if="errors.postal_code" class="mt-2 text-sm text-red-600">
                            {{ errors.postal_code }}
                        </p>
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700">
                            Pays
                        </label>
                        <div class="mt-1">
                            <input
                                id="country"
                                v-model="form.country"
                                name="country"
                                type="text"
                                required
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.country ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="France"
                            />
                        </div>
                        <p v-if="errors.country" class="mt-2 text-sm text-red-600">
                            {{ errors.country }}
                        </p>
                    </div>

                    <!-- Blood Type -->
                    <div>
                        <label for="blood_type_id" class="block text-sm font-medium text-gray-700">
                            Groupe sanguin
                        </label>
                        <div class="mt-1">
                            <select
                                id="blood_type_id"
                                v-model="form.blood_type_id"
                                name="blood_type_id"
                                :class="[
                                    'block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.blood_type_id ? 'border-red-300' : 'border-gray-300'
                                ]"
                            >
                                <option value="">Sélectionnez votre groupe sanguin</option>
                                <option value="1">A+</option>
                                <option value="2">A-</option>
                                <option value="3">B+</option>
                                <option value="4">B-</option>
                                <option value="5">AB+</option>
                                <option value="6">AB-</option>
                                <option value="7">O+</option>
                                <option value="8">O-</option>
                            </select>
                        </div>
                        <p v-if="errors.blood_type_id" class="mt-2 text-sm text-red-600">
                            {{ errors.blood_type_id }}
                        </p>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                            Date de naissance
                        </label>
                        <div class="mt-1">
                            <input
                                id="date_of_birth"
                                v-model="form.date_of_birth"
                                name="date_of_birth"
                                type="date"
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.date_of_birth ? 'border-red-300' : 'border-gray-300'
                                ]"
                            />
                        </div>
                        <p v-if="errors.date_of_birth" class="mt-2 text-sm text-red-600">
                            {{ errors.date_of_birth }}
                        </p>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">
                            Genre
                        </label>
                        <div class="mt-1">
                            <select
                                id="gender"
                                v-model="form.gender"
                                name="gender"
                                :class="[
                                    'block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.gender ? 'border-red-300' : 'border-gray-300'
                                ]"
                            >
                                <option value="">Sélectionnez votre genre</option>
                                <option value="male">Homme</option>
                                <option value="female">Femme</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        <p v-if="errors.gender" class="mt-2 text-sm text-red-600">
                            {{ errors.gender }}
                        </p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Rôle
                        </label>
                        <div class="mt-1">
                            <select
                                id="role"
                                v-model="form.role"
                                name="role"
                                required
                                :class="[
                                    'block w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.role ? 'border-red-300' : 'border-gray-300'
                                ]"
                            >
                                <option value="">Sélectionnez votre rôle</option>
                                <option value="donor">Donneur de sang</option>
                                <option value="blood_bank">Banque de sang</option>
                                <option value="doctor">Médecin</option>
                            </select>
                        </div>
                        <p v-if="errors.role" class="mt-2 text-sm text-red-600">
                            {{ errors.role }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <div class="mt-1">
                            <input
                                id="password"
                                v-model="form.password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                required
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.password ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="Minimum 8 caractères"
                            />
                        </div>
                        <p v-if="errors.password" class="mt-2 text-sm text-red-600">
                            {{ errors.password }}
                        </p>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe
                        </label>
                        <div class="mt-1">
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                required
                                :class="[
                                    'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm',
                                    errors.password_confirmation ? 'border-red-300' : 'border-gray-300'
                                ]"
                                placeholder="Répétez votre mot de passe"
                            />
                        </div>
                        <p v-if="errors.password_confirmation" class="mt-2 text-sm text-red-600">
                            {{ errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ processing ? 'Création du compte...' : 'Créer mon compte' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import AuthService from '@/Services/AuthService'

const router = useRouter()

// Form data
const form = reactive({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    postal_code: '',
    country: '',
    blood_type_id: '',
    date_of_birth: '',
    gender: '',
    role: '',
    password: '',
    password_confirmation: ''
})

// State
const processing = ref(false)
const errors = ref({})
const generalError = ref('')
const successMessage = ref('')

// Methods
const submit = async () => {
    processing.value = true
    errors.value = {}
    generalError.value = ''
    successMessage.value = ''

    try {
        const response = await AuthService.register(form)

        successMessage.value = response.message || 'Compte créé avec succès !'

        // Redirection vers le dashboard après un court délai
        setTimeout(() => {
            router.push('/dashboard')
        }, 1000)

    } catch (error) {
        if (error.type === 'validation') {
            errors.value = error.errors
        } else {
            generalError.value = error.message || 'Une erreur s\'est produite lors de l\'inscription'
        }
    } finally {
        processing.value = false
    }
}
</script>
