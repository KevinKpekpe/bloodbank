<template>
  <div class="stock-manager">
    <!-- En-tête avec statistiques -->
    <div class="mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
          <h4 class="text-sm font-medium text-blue-800">Total en stock</h4>
          <p class="text-2xl font-bold text-blue-900">{{ totalQuantity }}ml</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
          <h4 class="text-sm font-medium text-yellow-800">Stocks faibles</h4>
          <p class="text-2xl font-bold text-yellow-900">{{ lowStockCount }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
          <h4 class="text-sm font-medium text-green-800">Stocks OK</h4>
          <p class="text-2xl font-bold text-green-900">{{ okStockCount }}</p>
        </div>
      </div>
    </div>

    <!-- Tableau des stocks -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
      <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          Gestion des stocks par groupe sanguin
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
          Modifiez les quantités, seuils et capacités directement dans le tableau
        </p>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Groupe sanguin
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Quantité (ml)
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Seuil minimum (ml)
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Capacité max (ml)
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="stock in stocks" :key="stock.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                      <span class="text-sm font-medium text-red-800">{{ stock.blood_type?.name }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ stock.blood_type?.name }}</div>
                    <div class="text-sm text-gray-500">{{ stock.blood_type?.description }}</div>
                  </div>
                </div>
              </td>

              <!-- Quantité -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="editingStock?.id === stock.id && editingField === 'quantity_ml'">
                  <input
                    v-model.number="editingStock.quantity_ml"
                    type="number"
                    min="0"
                    :max="stock.maximum_capacity"
                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    @blur="saveStock(stock.id)"
                    @keyup.enter="saveStock(stock.id)"
                  />
                </div>
                <div v-else @click="startEditing(stock, 'quantity_ml')" class="cursor-pointer">
                  <span :class="getQuantityClass(stock)">{{ stock.quantity_ml }}ml</span>
                </div>
              </td>

              <!-- Seuil minimum -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="editingStock?.id === stock.id && editingField === 'minimum_threshold'">
                  <input
                    v-model.number="editingStock.minimum_threshold"
                    type="number"
                    min="0"
                    :max="stock.maximum_capacity"
                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    @blur="saveStock(stock.id)"
                    @keyup.enter="saveStock(stock.id)"
                  />
                </div>
                <div v-else @click="startEditing(stock, 'minimum_threshold')" class="cursor-pointer">
                  <span class="text-sm text-gray-900">{{ stock.minimum_threshold }}ml</span>
                </div>
              </td>

              <!-- Capacité maximale -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="editingStock?.id === stock.id && editingField === 'maximum_capacity'">
                  <input
                    v-model.number="editingStock.maximum_capacity"
                    type="number"
                    min="0"
                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm"
                    @blur="saveStock(stock.id)"
                    @keyup.enter="saveStock(stock.id)"
                  />
                </div>
                <div v-else @click="startEditing(stock, 'maximum_capacity')" class="cursor-pointer">
                  <span class="text-sm text-gray-900">{{ stock.maximum_capacity }}ml</span>
                </div>
              </td>

              <!-- Statut -->
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(stock)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                  {{ getStatusText(stock) }}
                </span>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewHistory(stock.id)"
                  class="text-indigo-600 hover:text-indigo-900 mr-3"
                >
                  Historique
                </button>
                <button
                  @click="adjustStock(stock)"
                  class="text-green-600 hover:text-green-900"
                >
                  Ajuster
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal pour ajuster le stock -->
    <div v-if="showAdjustModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            Ajuster le stock {{ selectedStock?.blood_type?.name }}
          </h3>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Type de mouvement
            </label>
            <select v-model="adjustmentData.movement_type" class="w-full px-3 py-2 border border-gray-300 rounded-md">
              <option value="in">Ajout</option>
              <option value="out">Retrait</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Quantité (ml)
            </label>
            <input
              v-model.number="adjustmentData.quantity_ml"
              type="number"
              min="1"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
            />
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Raison
            </label>
            <textarea
              v-model="adjustmentData.reason"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md"
              placeholder="Raison de l'ajustement..."
            ></textarea>
          </div>

          <div class="flex justify-end space-x-3">
            <button
              @click="showAdjustModal = false"
              class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400"
            >
              Annuler
            </button>
            <button
              @click="confirmAdjustment"
              :disabled="!adjustmentData.quantity_ml || !adjustmentData.reason"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              Confirmer
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal pour l'historique -->
    <div v-if="showHistoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
              Historique des modifications - {{ selectedStock?.blood_type?.name }}
            </h3>
            <button
              @click="showHistoryModal = false"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <div v-if="stockHistory.length === 0" class="text-center py-8">
            <p class="text-gray-500">Aucun historique disponible</p>
          </div>

          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ancienne quantité</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nouvelle quantité</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Différence</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Raison</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Modifié par</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="history in stockHistory" :key="history.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(history.created_at) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ history.old_quantity }}ml
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ history.new_quantity }}ml
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="getDifferenceClass(history)">
                      {{ history.quantity_difference > 0 ? '+' : '' }}{{ history.quantity_difference }}ml
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900">
                    {{ history.reason }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ history.updated_by?.name || 'Système' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import StockService from '@/Services/StockService'

const props = defineProps({
  stocks: {
    type: Array,
    required: true
  },
  bank: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['refresh'])

// État local
const editingStock = ref(null)
const editingField = ref(null)
const showAdjustModal = ref(false)
const showHistoryModal = ref(false)
const selectedStock = ref(null)
const stockHistory = ref([])

// Données pour l'ajustement
const adjustmentData = ref({
  movement_type: 'in',
  quantity_ml: null,
  reason: ''
})

// Computed properties
const totalQuantity = computed(() => {
  return props.stocks.reduce((sum, stock) => sum + stock.quantity_ml, 0)
})

const lowStockCount = computed(() => {
  return props.stocks.filter(stock => stock.quantity_ml <= stock.minimum_threshold).length
})

const okStockCount = computed(() => {
  return props.stocks.filter(stock => stock.quantity_ml > stock.minimum_threshold).length
})

// Méthodes
const startEditing = (stock, field) => {
  editingStock.value = { ...stock }
  editingField.value = field
}

const saveStock = async (stockId) => {
  if (!editingStock.value) return

  try {
    const stockData = {
      quantity_ml: editingStock.value.quantity_ml,
      minimum_threshold: editingStock.value.minimum_threshold,
      maximum_capacity: editingStock.value.maximum_capacity,
      reason: 'Modification manuelle via interface'
    }

    await StockService.update(stockId, stockData)

    // Réinitialiser l'édition
    editingStock.value = null
    editingField.value = null

    // Rafraîchir les données
    emit('refresh')

  } catch (error) {
    console.error('Erreur lors de la sauvegarde:', error)
    alert('Erreur lors de la sauvegarde du stock')
  }
}

const adjustStock = (stock) => {
  selectedStock.value = stock
  adjustmentData.value = {
    movement_type: 'in',
    quantity_ml: null,
    reason: ''
  }
  showAdjustModal.value = true
}

const confirmAdjustment = async () => {
  if (!selectedStock.value || !adjustmentData.value.quantity_ml || !adjustmentData.value.reason) {
    return
  }

  try {
    await StockService.adjust(selectedStock.value.id, adjustmentData.value)

    showAdjustModal.value = false
    selectedStock.value = null
    adjustmentData.value = { movement_type: 'in', quantity_ml: null, reason: '' }

    // Rafraîchir les données
    emit('refresh')

  } catch (error) {
    console.error('Erreur lors de l\'ajustement:', error)
    alert('Erreur lors de l\'ajustement du stock')
  }
}

const viewHistory = async (stockId) => {
  try {
    const response = await StockService.getHistory(stockId)
    stockHistory.value = response.data || response
    showHistoryModal.value = true
  } catch (error) {
    console.error('Erreur lors de la récupération de l\'historique:', error)
    alert('Erreur lors de la récupération de l\'historique')
  }
}

const getQuantityClass = (stock) => {
  if (stock.quantity_ml === 0) return 'text-red-600 font-semibold'
  if (stock.quantity_ml <= stock.minimum_threshold) return 'text-yellow-600 font-semibold'
  return 'text-green-600 font-semibold'
}

const getStatusClass = (stock) => {
  if (stock.quantity_ml === 0) return 'bg-red-100 text-red-800'
  if (stock.quantity_ml <= stock.minimum_threshold) return 'bg-yellow-100 text-yellow-800'
  return 'bg-green-100 text-green-800'
}

const getStatusText = (stock) => {
  if (stock.quantity_ml === 0) return 'Vide'
  if (stock.quantity_ml <= stock.minimum_threshold) return 'Faible'
  return 'OK'
}

const getDifferenceClass = (history) => {
  const diff = history.quantity_difference
  if (diff > 0) return 'text-green-600 font-semibold'
  if (diff < 0) return 'text-red-600 font-semibold'
  return 'text-gray-600'
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString('fr-FR')
}

// Réinitialiser l'édition quand on clique ailleurs
const resetEditing = () => {
  editingStock.value = null
  editingField.value = null
}

// Écouter les clics en dehors des champs d'édition
watch(editingStock, (newVal) => {
  if (newVal) {
    setTimeout(() => {
      document.addEventListener('click', resetEditing, { once: true })
    }, 100)
  }
})
</script>
