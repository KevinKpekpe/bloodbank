<template>
  <div>
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type de sang</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantité (ml)</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Seuil min. (ml)</th>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Capacité max. (ml)</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="stock in stocks" :key="stock.id">
          <td class="px-4 py-2 font-semibold">{{ stock.blood_type?.name }}</td>
          <td class="px-4 py-2">
            <template v-if="editRows[stock.id]?.editing">
              <input v-model.number="editRows[stock.id].quantity_ml" type="number" min="0" class="w-24 px-2 py-1 border rounded" />
            </template>
            <template v-else>
              {{ stock.quantity_ml }}
            </template>
          </td>
          <td class="px-4 py-2">
            <template v-if="editRows[stock.id]?.editing">
              <input v-model.number="editRows[stock.id].minimum_threshold" type="number" min="0" class="w-20 px-2 py-1 border rounded" />
            </template>
            <template v-else>
              {{ stock.minimum_threshold }}
            </template>
          </td>
          <td class="px-4 py-2">
            <template v-if="editRows[stock.id]?.editing">
              <input v-model.number="editRows[stock.id].maximum_capacity" type="number" min="0" class="w-24 px-2 py-1 border rounded" />
            </template>
            <template v-else>
              {{ stock.maximum_capacity }}
            </template>
          </td>
          <td class="px-4 py-2 text-right">
            <button v-if="!editRows[stock.id]?.editing" @click="startEdit(stock)" class="text-sm text-red-600 hover:underline">Modifier</button>
            <template v-else>
              <button @click="saveEdit(stock)" class="text-sm text-green-600 font-semibold mr-2">Enregistrer</button>
              <button @click="cancelEdit(stock)" class="text-sm text-gray-500">Annuler</button>
            </template>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="successMessage" class="mt-4 text-green-600">{{ successMessage }}</div>
    <div v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import StockService from '@/Services/StockService'

const props = defineProps({
  stocks: Array,
  bank: Object
})
const emit = defineEmits(['refresh'])

const editRows = reactive({})
const successMessage = ref('')
const errorMessage = ref('')

const startEdit = (stock) => {
  editRows[stock.id] = {
    editing: true,
    quantity_ml: stock.quantity_ml,
    minimum_threshold: stock.minimum_threshold,
    maximum_capacity: stock.maximum_capacity
  }
}

const cancelEdit = (stock) => {
  editRows[stock.id] = { editing: false }
}

const saveEdit = async (stock) => {
  successMessage.value = ''
  errorMessage.value = ''
  const row = editRows[stock.id]
  try {
    await StockService.update(stock.id, {
      quantity_ml: row.quantity_ml,
      minimum_threshold: row.minimum_threshold,
      maximum_capacity: row.maximum_capacity,
      reason: "Ajustement manuel par l'admin de la banque"
    })
    successMessage.value = 'Stock mis à jour !'
    editRows[stock.id] = { editing: false }
    emit('refresh')
  } catch (e) {
    errorMessage.value = e?.response?.data?.message || 'Erreur lors de la mise à jour.'
  }
}
</script>
