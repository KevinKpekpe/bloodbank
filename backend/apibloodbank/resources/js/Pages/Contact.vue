<template>
    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Contactez-nous</h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Nous sommes là pour vous aider. N'hésitez pas à nous contacter pour toute question ou assistance.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>

                        <!-- Message d'erreur général -->
                        <div v-if="generalError" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            {{ generalError }}
                        </div>

                        <!-- Message de succès -->
                        <div v-if="successMessage" class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                            {{ successMessage }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom complet
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    placeholder="Votre nom complet"
                                />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    placeholder="votre@email.com"
                                />
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sujet
                                </label>
                                <select
                                    id="subject"
                                    v-model="form.subject"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                >
                                    <option value="">Sélectionnez un sujet</option>
                                    <option value="general">Question générale</option>
                                    <option value="technical">Problème technique</option>
                                    <option value="donation">Question sur le don</option>
                                    <option value="partnership">Partenariat</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    rows="6"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                    placeholder="Votre message..."
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                :disabled="processing"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg v-if="processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ processing ? 'Envoi...' : 'Envoyer le message' }}
                            </button>
                        </form>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-8">
                        <!-- Contact Methods -->
                        <div class="bg-white rounded-lg shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Autres moyens de nous contacter</h2>

                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                                        <p class="text-gray-600">contact@bloodbank.com</p>
                                        <p class="text-sm text-gray-500">Réponse sous 24h</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Téléphone</h3>
                                        <p class="text-gray-600">+33 1 23 45 67 89</p>
                                        <p class="text-sm text-gray-500">Lun-Ven 9h-18h</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Adresse</h3>
                                        <p class="text-gray-600">123 Rue de la Paix<br>75001 Paris, France</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FAQ -->
                        <div class="bg-white rounded-lg shadow-lg p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Questions fréquentes</h2>

                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Comment faire un don de sang ?</h3>
                                    <p class="text-gray-600">Inscrivez-vous sur notre plateforme et trouvez la banque de sang la plus proche de chez vous.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Quels sont les critères pour donner ?</h3>
                                    <p class="text-gray-600">Vous devez avoir entre 18 et 70 ans, peser plus de 50kg et être en bonne santé.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Combien de temps dure un don ?</h3>
                                    <p class="text-gray-600">Un don de sang prend environ 45 minutes, incluant l'entretien pré-don et la collation.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// Form data
const form = reactive({
    name: '',
    email: '',
    subject: '',
    message: ''
})

// State
const processing = ref(false)
const generalError = ref('')
const successMessage = ref('')

// Methods
const submit = async () => {
    processing.value = true
    generalError.value = ''
    successMessage.value = ''

    try {
        // Pour l'instant, on simule l'envoi car l'API n'est pas encore implémentée
        // await ContactService.send(form)

        // Simulation d'un délai
        await new Promise(resolve => setTimeout(resolve, 1000))

        successMessage.value = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.'

        // Réinitialiser le formulaire
        form.name = ''
        form.email = ''
        form.subject = ''
        form.message = ''

    } catch (error) {
        generalError.value = 'Une erreur s\'est produite lors de l\'envoi du message. Veuillez réessayer.'
    } finally {
        processing.value = false
    }
}
</script>
