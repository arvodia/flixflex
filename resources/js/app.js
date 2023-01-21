import './bootstrap';
import 'flowbite';


import router from "./router"
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'

const app = createApp(App);
app.use(router);
app.use(createPinia());
app.mount("#app");
