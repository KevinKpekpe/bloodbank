<template>
  <AppLayout title="Dashboard Banque de Sang">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard - {{ bank?.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Informations de la banque -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
          <div class="p-6 text-gray-900">
            <h3 class="text-lg font-semibold mb-4">Informations de la banque</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p><strong>Nom :</strong> {{ bank?.name }}</p>
                <p><strong>Adresse :</strong> {{ bank?.address }}</p>
                <p><strong>Ville :</strong> {{ bank?.city }}</p>
                <p><strong>Code postal :</strong> {{ bank?.postal_code }}</p>
              </div>
              <div>
                <p><strong>Téléphone :</strong> {{ bank?.phone }}</p>
                <p><strong>Email :</strong> {{ bank?.email }}</p>
                <p><strong>Statut :</strong>
                  <span :class="bank?.is_verified ? 'text-green-600' : 'text-yellow-600'">
                    {{ bank?.is_verified ? 'Vérifiée' : 'En attente de vérification' }}
                  </span>
                </p>
                <p><strong>Date de création :</strong> {{ formatDate(bank?.created_at) }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Total des stocks</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ totalStocks }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Stocks faibles</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ lowStocksCount }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Stocks OK</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ okStocksCount }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
              <div class="flex items-center">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <p class="text-sm font-medium text-gray-500">Capacité utilisée</p>
                  <p class="text-2xl font-semibold text-gray-900">{{ capacityUsage }}%</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Gestion des stocks -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <div class="flex justify-between items-center mb-6">
              <h3 class="text-lg font-semibold">Gestion des stocks</h3>
              <button
                @click="refreshStocks"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                :disabled="loading"
              >
                <span v-if="loading">Chargement...</span>
                <span v-else>Actualiser</span>
              </button>
            </div>

            <div v-if="loading" class="text-center py-8">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
              <p class="mt-2 text-gray-600">Chargement des stocks...</p>
            </div>

            <div v-else-if="stocks.length === 0" class="text-center py-8">
              <p class="text-gray-600">Aucun stock trouvé pour cette banque.</p>
            </div>

            <div v-else>
              <StockManager
                :stocks="stocks"
                :bank="bank"
                @refresh="refreshStocks"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StockManager from '@/Pages/BloodBank/StockManager.vue'
import StockService from '@/Services/StockService'
import AuthService from '@/Services/AuthService'

const bank = ref(null)
const stocks = ref([])
const loading = ref(false)

// Computed properties pour les statistiques
const totalStocks = computed(() => stocks.value.length)
const lowStocksCount = computed(() =>
  stocks.value.filter(stock => stock.quantity_ml <= stock.minimum_threshold).length
)
const okStocksCount = computed(() =>
  stocks.value.filter(stock => stock.quantity_ml > stock.minimum_threshold).length
)
const capacityUsage = computed(() => {
  if (stocks.value.length === 0) return 0
  const totalCapacity = stocks.value.reduce((sum, stock) => sum + stock.maximum_capacity, 0)
  const totalQuantity = stocks.value.reduce((sum, stock) => sum + stock.quantity_ml, 0)
  return totalCapacity > 0 ? Math.round((totalQuantity / totalCapacity) * 100) : 0
})

const loadBankInfo = async () => {
  try {
    const user = await AuthService.getCurrentUser()
    if (user && user.blood_bank_id) {
      // Ici vous devriez appeler une API pour récupérer les infos de la banque
      // Pour l'instant, on utilise les données de l'utilisateur
      bank.value = {
        id: user.blood_bank_id,
        name: user.blood_bank?.name || 'Banque de sang',
        address: user.blood_bank?.address || '',
        city: user.blood_bank?.city || '',
        postal_code: user.blood_bank?.postal_code || '',
        phone: user.blood_bank?.phone || '',
        email: user.blood_bank?.email || '',
        is_verified: user.blood_bank?.is_verified || false,
        created_at: user.blood_bank?.created_at || new Date()
      }
    }
  } catch (error) {
    console.error('Erreur lors du chargement des infos de la banque:', error)
  }
}

const loadStocks = async () => {
  if (!bank.value?.id) return

  loading.value = true
  try {
    const response = await StockService.getStocks(bank.value.id)
    stocks.value = response.data || response
  } catch (error) {
    console.error('Erreur lors du chargement des stocks:', error)
  } finally {
    loading.value = false
  }
}

const refreshStocks = async () => {
  await loadStocks()
}

const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('fr-FR')
}

onMounted(async () => {
  await loadBankInfo()
  await loadStocks()
})
</script>
