import {createRouter, createWebHistory} from 'vue-router';
import MainLayout from "@/layouts/MainLayout";
import MainView from "@/views/MainView";
import NotFound from "@/views/NotFound";

const routes = [{
    component: MainLayout, path: "/", children: [{
        path: "/home", name: "home", alias: '/', component: MainView,
    }, {
        path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound,
    },],
},

];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL), routes,
});

export default router;
