import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue'
import router from './router'

// Import du composant principal
import App from './App.vue'

const app = createApp(App)

// Utiliser le router
app.use(router)

// Monter l'application
app.mount('#app')
