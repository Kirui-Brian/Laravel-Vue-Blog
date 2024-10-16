import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Create the event bus
const eventBus = createApp({});

const app = createApp(App)
app.config.globalProperties.$eventBus = eventBus;

app.use(router)

app.mount('#app')
