import {createRouter, createWebHistory} from 'vue-router';
import NotFound from "@/views/NotFound";
import MainLayout from "@/layouts/MainLayout";
import MainView from "@/views/MainView";
import EventAbout from "@/views/event/EventAbout";

const routes = [{
    component: MainLayout, path: "/", children: [{
        path: "/home", name: "home", alias: '/', component: MainView,
    }, {
        path: "/event", name: "event_about", component: EventAbout,
    }, {
        path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound,
    },],
},

];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL), routes,
});

export default router;
