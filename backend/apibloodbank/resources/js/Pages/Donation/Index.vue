<template>
  <AppLayout>
    <div class="min-h-screen bg-gray-50 py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">Gestion des Dons de Sang</h1>
          <p class="mt-2 text-gray-600">Planifiez vos dons et suivez votre historique</p>
        </div>

        <!-- Statistiques du donneur -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Dons Totaux</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.totalDonations }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Dernier Don</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.lastDonation || 'Aucun' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Prochain Don</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.nextDonation || 'Non planifié' }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Éligible</p>
                <p class="text-2xl font-semibold text-gray-900">{{ stats.eligible ? 'Oui' : 'Non' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Onglets -->
        <div class="bg-white rounded-lg shadow">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  activeTab === tab.id
                    ? 'border-red-500 text-red-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                  'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
                ]"
              >
                {{ tab.name }}
              </button>
            </nav>
          </div>

          <!-- Contenu des onglets -->
          <div class="p-6">
            <!-- Onglet Prise de rendez-vous -->
            <div v-if="activeTab === 'appointment'" class="space-y-6">
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulaire de rendez-vous -->
                <div class="space-y-6">
                  <h3 class="text-lg font-medium text-gray-900">Prendre un rendez-vous</h3>

                  <form @submit.prevent="bookAppointment" class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Banque de sang</label>
                      <select v-model="appointmentForm.blood_bank_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Sélectionner une banque</option>
                        <option v-for="bank in bloodBanks" :key="bank.id" :value="bank.id">
                          {{ bank.name }} - {{ bank.city }}
                        </option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Date souhaitée</label>
                      <input
                        type="date"
                        v-model="appointmentForm.preferred_date"
                        :min="minDate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                      >
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Heure préférée</label>
                      <select v-model="appointmentForm.preferred_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Sélectionner une heure</option>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="14:00">14:00</option>
                        <option value="15:00">15:00</option>
                        <option value="16:00">16:00</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Type de don</label>
                      <select v-model="appointmentForm.donation_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="whole_blood">Sang total</option>
                        <option value="plasma">Plasma</option>
                        <option value="platelets">Plaquettes</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-sm font-medium text-gray-700">Notes (optionnel)</label>
                      <textarea
                        v-model="appointmentForm.notes"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                        placeholder="Informations supplémentaires..."
                      ></textarea>
                    </div>

                    <button
                      type="submit"
                      :disabled="loading"
                      class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50"
                    >
                      <span v-if="loading">Réservation en cours...</span>
                      <span v-else>Prendre rendez-vous</span>
                    </button>
                  </form>
                </div>

                <!-- Calendrier -->
                <div class="space-y-6">
                  <h3 class="text-lg font-medium text-gray-900">Calendrier des disponibilités</h3>
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-center text-sm text-gray-600">
                      Sélectionnez une banque de sang pour voir les disponibilités
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Onglet Historique -->
            <div v-if="activeTab === 'history'" class="space-y-6">
              <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Historique des dons</h3>
                <div class="flex space-x-2">
                  <select v-model="historyFilter" class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous les dons</option>
                    <option value="completed">Complétés</option>
                    <option value="scheduled">Planifiés</option>
                    <option value="cancelled">Annulés</option>
                  </select>
                </div>
              </div>

              <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                  <li v-for="donation in filteredHistory" :key="donation.id" class="px-6 py-4">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center">
                        <div class="flex-shrink-0">
                          <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                              <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ donation.blood_bank_name }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ formatDate(donation.donation_date) }} - {{ donation.donation_type }}
                          </div>
                        </div>
                      </div>
                      <div class="flex items-center space-x-4">
                        <span :class="getStatusClass(donation.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                          {{ getStatusText(donation.status) }}
                        </span>
                        <button
                          v-if="donation.status === 'scheduled'"
                          @click="cancelDonation(donation.id)"
                          class="text-red-600 hover:text-red-900 text-sm font-medium"
                        >
                          Annuler
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Onglet Informations -->
            <div v-if="activeTab === 'info'" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Critères d'éligibilité -->
                <div class="bg-blue-50 rounded-lg p-6">
                  <h3 class="text-lg font-medium text-blue-900 mb-4">Critères d'éligibilité</h3>
                  <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Âge entre 18 et 70 ans
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Poids minimum de 50 kg
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Bonne santé générale
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Pas de don dans les 56 derniers jours
                    </li>
                  </ul>
                </div>

                <!-- Préparation au don -->
                <div class="bg-green-50 rounded-lg p-6">
                  <h3 class="text-lg font-medium text-green-900 mb-4">Préparation au don</h3>
                  <ul class="space-y-2 text-sm text-green-800">
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Bien dormir la veille
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Manger légèrement avant
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      S'hydrater suffisamment
                    </li>
                    <li class="flex items-start">
                      <svg class="w-4 h-4 text-green-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                      Apporter une pièce d'identité
                    </li>
                  </ul>
                </div>
              </div>

              <!-- FAQ -->
              <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Questions fréquentes</h3>
                <div class="space-y-4">
                  <div v-for="(faq, index) in faqs" :key="index" class="border-b border-gray-200 pb-4">
                    <button
                      @click="toggleFaq(index)"
                      class="flex justify-between items-center w-full text-left"
                    >
                      <h4 class="text-sm font-medium text-gray-900">{{ faq.question }}</h4>
                      <svg
                        :class="['w-5 h-5 text-gray-500 transform transition-transform', expandedFaqs.includes(index) ? 'rotate-180' : '']"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                    </button>
                    <div v-if="expandedFaqs.includes(index)" class="mt-2 text-sm text-gray-600">
                      {{ faq.answer }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'DonationIndex',
  components: {
    AppLayout
  },
  setup() {
    const activeTab = ref('appointment')
    const loading = ref(false)
    const bloodBanks = ref([])
    const donationHistory = ref([])
    const historyFilter = ref('')
    const expandedFaqs = ref([])

    const stats = ref({
      totalDonations: 0,
      lastDonation: null,
      nextDonation: null,
      eligible: true
    })

    const appointmentForm = ref({
      blood_bank_id: '',
      preferred_date: '',
      preferred_time: '',
      donation_type: 'whole_blood',
      notes: ''
    })

    const tabs = [
      { id: 'appointment', name: 'Prendre rendez-vous' },
      { id: 'history', name: 'Historique' },
      { id: 'info', name: 'Informations' }
    ]

    const faqs = [
      {
        question: "Combien de temps dure un don de sang ?",
        answer: "Un don de sang complet prend généralement 10-15 minutes, plus le temps de préparation et de repos après le don."
      },
      {
        question: "À quelle fréquence puis-je donner mon sang ?",
        answer: "Vous pouvez donner votre sang tous les 56 jours (8 semaines) pour les hommes et tous les 84 jours (12 semaines) pour les femmes."
      },
      {
        question: "Y a-t-il des risques pour ma santé ?",
        answer: "Le don de sang est très sûr. Les aiguilles utilisées sont stériles et à usage unique. Vous pouvez ressentir une légère fatigue temporaire."
      },
      {
        question: "Que se passe-t-il après le don ?",
        answer: "Vous recevrez des instructions de soins post-don et devrez vous reposer pendant 15-20 minutes avant de repartir."
      }
    ]

    const minDate = computed(() => {
      const today = new Date()
      const tomorrow = new Date(today)
      tomorrow.setDate(tomorrow.getDate() + 1)
      return tomorrow.toISOString().split('T')[0]
    })

    const filteredHistory = computed(() => {
      if (!historyFilter.value) return donationHistory.value
      return donationHistory.value.filter(donation => donation.status === historyFilter.value)
    })

    const loadBloodBanks = async () => {
      try {
        const response = await axios.get('/api/blood-banks')
        bloodBanks.value = response.data.data || []
      } catch (error) {
        console.error('Erreur lors du chargement des banques de sang:', error)
      }
    }

    const loadDonationHistory = async () => {
      try {
        const response = await axios.get('/api/donations/history')
        donationHistory.value = response.data.data || []
        updateStats()
      } catch (error) {
        console.error('Erreur lors du chargement de l\'historique:', error)
      }
    }

    const updateStats = () => {
      const completed = donationHistory.value.filter(d => d.status === 'completed')
      stats.value.totalDonations = completed.length

      if (completed.length > 0) {
        const lastDonation = completed[completed.length - 1]
        stats.value.lastDonation = formatDate(lastDonation.donation_date)
      }

      const scheduled = donationHistory.value.filter(d => d.status === 'scheduled')
      if (scheduled.length > 0) {
        const nextDonation = scheduled[0]
        stats.value.nextDonation = formatDate(nextDonation.donation_date)
      }
    }

    const bookAppointment = async () => {
      loading.value = true
      try {
        await axios.post('/api/donations/appointments', appointmentForm.value)

        // Réinitialiser le formulaire
        appointmentForm.value = {
          blood_bank_id: '',
          preferred_date: '',
          preferred_time: '',
          donation_type: 'whole_blood',
          notes: ''
        }

        // Recharger l'historique
        await loadDonationHistory()

        alert('Rendez-vous réservé avec succès !')
      } catch (error) {
        console.error('Erreur lors de la réservation:', error)
        alert('Erreur lors de la réservation du rendez-vous')
      } finally {
        loading.value = false
      }
    }

    const cancelDonation = async (donationId) => {
      if (!confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) return

      try {
        await axios.delete(`/api/donations/appointments/${donationId}`)
        await loadDonationHistory()
        alert('Rendez-vous annulé avec succès')
      } catch (error) {
        console.error('Erreur lors de l\'annulation:', error)
        alert('Erreur lors de l\'annulation du rendez-vous')
      }
    }

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString('fr-FR')
    }

    const getStatusClass = (status) => {
      const classes = {
        completed: 'bg-green-100 text-green-800',
        scheduled: 'bg-blue-100 text-blue-800',
        cancelled: 'bg-red-100 text-red-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }

    const getStatusText = (status) => {
      const texts = {
        completed: 'Complété',
        scheduled: 'Planifié',
        cancelled: 'Annulé'
      }
      return texts[status] || status
    }

    const toggleFaq = (index) => {
      const position = expandedFaqs.value.indexOf(index)
      if (position > -1) {
        expandedFaqs.value.splice(position, 1)
      } else {
        expandedFaqs.value.push(index)
      }
    }

    onMounted(() => {
      loadBloodBanks()
      loadDonationHistory()
    })

    return {
      activeTab,
      loading,
      bloodBanks,
      donationHistory,
      historyFilter,
      expandedFaqs,
      stats,
      appointmentForm,
      tabs,
      faqs,
      minDate,
      filteredHistory,
      bookAppointment,
      cancelDonation,
      formatDate,
      getStatusClass,
      getStatusText,
      toggleFaq
    }
  }
}
</script>
