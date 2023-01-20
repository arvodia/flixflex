/**
 * @author  : Sidi Said Redouane <sidisaidredouane@live.com>
 * @agency  : EURL ARVODIA
 * @email   : arvodia@hotmail.com
 * @date    : 2021
 * @license : All Rights Reserved
 * @update  : 8 avr. 2022
 */
 import { createRouter, createWebHistory } from "vue-router";

 // 1. Define route components.
 // These can be imported from other files
 import Home from "../pages/Home.vue";
 import Film from "../pages/Film.vue";
 import Serie from "../pages/Serie.vue";
 
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
      path: "/film",
      name: "Film",
      component: Film,
      alias: "/film"
    },
    {
      path: "/serie",
      name: "Serie",
      component: Serie
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
 
 export default router;