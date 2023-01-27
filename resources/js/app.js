import './bootstrap';
import 'flowbite';


import router from "./router";
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import useUserStore from './stores/user.js';
import apiPrivate from './api/apiPrivate.js';


const app = createApp(App);
app.use(createPinia());

const store = useUserStore();
let token = sessionStorage.getItem("token");
if (token) {
    apiPrivate(token).get('/api/account')
            .then((response) => {
                if (response.data && response.data.data.name) {
                    store.updateData({name: response.data.data.name});
                    store.setAuth();
                } else {
                    store.resetState();
                }
                app.use(router);
                app.mount("#app");
            })
            .catch((error) => {
                store.resetState();
                app.use(router);
                app.mount("#app");
            });
} else {
    app.use(router);
    app.mount("#app");
}
