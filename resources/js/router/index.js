/**
 * @author  : Sidi Said Redouane <sidisaidredouane@live.com>
 * @agency  : EURL ARVODIA
 * @email   : arvodia@hotmail.com
 * @date    : 2021
 * @license : All Rights Reserved
 * @update  : 8 avr. 2022
 */
import { createRouter, createWebHistory } from "vue-router";
import useUserStore from '../stores/user.js'

        // 1. Define route components.
        // These can be imported from other files
import Home from "../pages/Home.vue";
import Films from "../pages/Films.vue";
import Series from "../pages/Series.vue";
import Register from "../pages/Register.vue";
import Login from "../pages/Login.vue";
import Favories from "../pages/Favories.vue";

// 2. Define some routes
// Each route should map to a component.
// We'll talk about nested routes later.
const routes = [
    {
        path: "/",
        name: "Home",
        component: Home
    },
    {
        path: "/films",
        name: "Films",
        component: Films,
        alias: "/films"
    },
    {
        path: "/series",
        name: "Series",
        component: Series,
    },
    {
        path: "/favories",
        name: "Favories",
        component: Favories,
        meta: {requiresAuth: true}
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
    },
    {
        path: "/login",
        name: "Login",
        component: Login
    },
];

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

// Middleware
router.beforeEach((to, from, next) => {
    const store = useUserStore()
    if (to.meta.requiresAuth && !store.isAuth) {
        next({name: 'Login'});
    } else {
        next();
    }
});

export default router;