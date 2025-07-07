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
                Mot de passe oublié
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Entrez votre adresse email pour recevoir un lien de réinitialisation
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
                            {{ processing ? 'Envoi...' : 'Envoyer le lien de réinitialisation' }}
                        </button>
                    </div>
                </form>

                <!-- Retour à la connexion -->
                <div class="mt-6 text-center">
                    <router-link to="/login" class="font-medium text-red-600 hover:text-red-500">
                        ← Retour à la connexion
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AuthService from '@/Services/AuthService'

// Form data
const form = reactive({
    email: ''
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
        // Pour l'instant, on simule l'envoi car l'API n'est pas encore implémentée
        // await AuthService.sendResetLink({ email: form.email })

        // Simulation d'un délai
        await new Promise(resolve => setTimeout(resolve, 1000))

        successMessage.value = 'Si cette adresse email existe dans notre base de données, vous recevrez un lien de réinitialisation dans quelques minutes.'

        // Réinitialiser le formulaire
        form.email = ''

    } catch (error) {
        if (error.type === 'validation') {
            errors.value = error.errors
        } else {
            generalError.value = error.message || 'Une erreur s\'est produite lors de l\'envoi'
        }
    } finally {
        processing.value = false
    }
}
</script>
