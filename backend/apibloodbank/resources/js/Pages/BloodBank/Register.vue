<template>
    <AppLayout>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">
                        Enregistrer une banque de sang
                    </h1>
                    <p class="text-xl text-red-100 max-w-3xl mx-auto">
                        Rejoignez notre réseau de banques de sang et contribuez à sauver des vies.
                        Créez votre compte administrateur et enregistrez votre établissement.
                    </p>
                </div>
            </div>
        </section>

        <!-- Registration Form -->
        <section class="bg-gray-50 py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Informations de la banque de sang</h2>
                        <p class="text-gray-600 mt-2">Remplissez les informations de votre établissement</p>
                    </div>

                    <form @submit.prevent="submitForm" class="p-6 space-y-8">
                        <!-- Informations de la banque -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom de la banque de sang *
                                </label>
                                <input
                                    id="bank_name"
                                    v-model="form.bank_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_name }"
                                />
                                <p v-if="errors.bank_name" class="mt-1 text-sm text-red-600">{{ errors.bank_name[0] }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="bank_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea
                                    id="bank_description"
                                    v-model="form.bank_description"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Description de votre établissement..."
                                ></textarea>
                            </div>

                            <div>
                                <label for="bank_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Téléphone *
                                </label>
                                <input
                                    id="bank_phone"
                                    v-model="form.bank_phone"
                                    type="tel"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_phone }"
                                />
                                <p v-if="errors.bank_phone" class="mt-1 text-sm text-red-600">{{ errors.bank_phone[0] }}</p>
                            </div>

                            <div>
                                <label for="bank_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input
                                    id="bank_email"
                                    v-model="form.bank_email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_email }"
                                />
                                <p v-if="errors.bank_email" class="mt-1 text-sm text-red-600">{{ errors.bank_email[0] }}</p>
                            </div>

                            <div>
                                <label for="bank_website" class="block text-sm font-medium text-gray-700 mb-2">
                                    Site web
                                </label>
                                <input
                                    id="bank_website"
                                    v-model="form.bank_website"
                                    type="url"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="https://www.votre-site.com"
                                />
                            </div>

                            <div>
                                <label for="bank_partnership_level" class="block text-sm font-medium text-gray-700 mb-2">
                                    Type d'établissement *
                                </label>
                                <select
                                    id="bank_partnership_level"
                                    v-model="form.bank_partnership_level"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_partnership_level }"
                                >
                                    <option value="">Sélectionnez un type</option>
                                    <option value="public">Établissement public</option>
                                    <option value="private">Établissement privé</option>
                                    <option value="associative">Association</option>
                                </select>
                                <p v-if="errors.bank_partnership_level" class="mt-1 text-sm text-red-600">{{ errors.bank_partnership_level[0] }}</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="bank_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse complète *
                                </label>
                                <input
                                    id="bank_address"
                                    v-model="form.bank_address"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_address }"
                                />
                                <p v-if="errors.bank_address" class="mt-1 text-sm text-red-600">{{ errors.bank_address[0] }}</p>
                            </div>

                            <div>
                                <label for="bank_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Ville *
                                </label>
                                <input
                                    id="bank_city"
                                    v-model="form.bank_city"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_city }"
                                />
                                <p v-if="errors.bank_city" class="mt-1 text-sm text-red-600">{{ errors.bank_city[0] }}</p>
                            </div>

                            <div>
                                <label for="bank_postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Code postal *
                                </label>
                                <input
                                    id="bank_postal_code"
                                    v-model="form.bank_postal_code"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    :class="{ 'border-red-500': errors.bank_postal_code }"
                                />
                                <p v-if="errors.bank_postal_code" class="mt-1 text-sm text-red-600">{{ errors.bank_postal_code[0] }}</p>
                            </div>

                            <div>
                                <label for="bank_country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pays
                                </label>
                                <input
                                    id="bank_country"
                                    v-model="form.bank_country"
                                    type="text"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="France"
                                />
                            </div>

                            <div>
                                <label for="bank_latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                    Latitude
                                </label>
                                <input
                                    id="bank_latitude"
                                    v-model="form.bank_latitude"
                                    type="number"
                                    step="any"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="48.8566"
                                />
                            </div>

                            <div>
                                <label for="bank_longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                    Longitude
                                </label>
                                <input
                                    id="bank_longitude"
                                    v-model="form.bank_longitude"
                                    type="number"
                                    step="any"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="2.3522"
                                />
                            </div>
                        </div>

                        <!-- Informations de l'administrateur -->
                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Informations de l'administrateur</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="admin_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Prénom *
                                    </label>
                                    <input
                                        id="admin_first_name"
                                        v-model="form.admin_first_name"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_first_name }"
                                    />
                                    <p v-if="errors.admin_first_name" class="mt-1 text-sm text-red-600">{{ errors.admin_first_name[0] }}</p>
                                </div>

                                <div>
                                    <label for="admin_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nom *
                                    </label>
                                    <input
                                        id="admin_last_name"
                                        v-model="form.admin_last_name"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_last_name }"
                                    />
                                    <p v-if="errors.admin_last_name" class="mt-1 text-sm text-red-600">{{ errors.admin_last_name[0] }}</p>
                                </div>

                                <div>
                                    <label for="admin_email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email *
                                    </label>
                                    <input
                                        id="admin_email"
                                        v-model="form.admin_email"
                                        type="email"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_email }"
                                    />
                                    <p v-if="errors.admin_email" class="mt-1 text-sm text-red-600">{{ errors.admin_email[0] }}</p>
                                </div>

                                <div>
                                    <label for="admin_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Téléphone *
                                    </label>
                                    <input
                                        id="admin_phone"
                                        v-model="form.admin_phone"
                                        type="tel"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_phone }"
                                    />
                                    <p v-if="errors.admin_phone" class="mt-1 text-sm text-red-600">{{ errors.admin_phone[0] }}</p>
                                </div>

                                <div>
                                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mot de passe *
                                    </label>
                                    <input
                                        id="admin_password"
                                        v-model="form.admin_password"
                                        type="password"
                                        required
                                        minlength="8"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_password }"
                                    />
                                    <p v-if="errors.admin_password" class="mt-1 text-sm text-red-600">{{ errors.admin_password[0] }}</p>
                                </div>

                                <div>
                                    <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirmer le mot de passe *
                                    </label>
                                    <input
                                        id="admin_password_confirmation"
                                        v-model="form.admin_password_confirmation"
                                        type="password"
                                        required
                                        minlength="8"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        :class="{ 'border-red-500': errors.admin_password_confirmation }"
                                    />
                                    <p v-if="errors.admin_password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.admin_password_confirmation[0] }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                                * Champs obligatoires
                            </p>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center"
                            >
                                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isSubmitting ? 'Enregistrement...' : 'Enregistrer la banque de sang' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Success Message -->
                <div v-if="successMessage" class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// State
const isSubmitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const errors = ref({})

// Form data
const form = reactive({
    // Banque de sang
    bank_name: '',
    bank_description: '',
    bank_phone: '',
    bank_email: '',
    bank_website: '',
    bank_address: '',
    bank_city: '',
    bank_postal_code: '',
    bank_country: 'France',
    bank_latitude: '',
    bank_longitude: '',
    bank_partnership_level: '',

    // Administrateur
    admin_first_name: '',
    admin_last_name: '',
    admin_email: '',
    admin_phone: '',
    admin_password: '',
    admin_password_confirmation: ''
})

// Methods
const submitForm = async () => {
    isSubmitting.value = true
    successMessage.value = ''
    errorMessage.value = ''
    errors.value = {}

    try {
        const response = await fetch('/blood-bank/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(form)
        })

        const data = await response.json()

        if (response.ok) {
            successMessage.value = data.message
            // Reset form
            Object.keys(form).forEach(key => {
                form[key] = key.includes('country') ? 'France' : ''
            })
        } else {
            if (data.errors) {
                errors.value = data.errors
            }
            errorMessage.value = data.message || 'Une erreur est survenue lors de l\'enregistrement.'
        }
    } catch (error) {
        console.error('Erreur lors de l\'enregistrement:', error)
        errorMessage.value = 'Une erreur de connexion est survenue.'
    } finally {
        isSubmitting.value = false
    }
}
</script>