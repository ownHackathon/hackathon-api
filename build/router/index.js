import {createRouter, createWebHistory} from 'vue-router';
import useUser from "@/composables/user";
import NotFound from '@/views/NotFound';
import MainLayout from '@/layouts/MainLayout';
import MainView from '@/views/MainView';
import LoginView from '@/views/LoginView';
import LogoutView from '@/views/LogoutView';
import EventAbout from '@/views/event/EventAbout';
import EventList from '@/views/event/EventList';
import EventEntry from '@/views/event/EventEntry';
import EventCreate from '@/views/event/EventCreate';

const routes = [{
  component: MainLayout, path: "/", children: [{
    path: "/home", name: "home", alias: '/', component: MainView,
  }, {
    path: "/login", name: "login", component: LoginView,
  }, {
    path: "/logout", name: "logout", component: LogoutView,
  }, {
    path: "/event/information", name: "event_general_information", component: EventAbout,
  }, {
    path: "/event/:id", name: "event_entry", component: EventEntry,
  }, {
    path: "/event/list", name: "event_list", component: EventList,
  }, {
    path: "/event/create", name: "event_create", component: EventCreate, meta: {requireAuth: true},
  }, {
    path: "/about", name: "about", component: MainView,
  }, {
    path: "/discord", name: "discord", beforeEnter() {
      location.href = 'https://discord.gg/VjrfCFKRgR';
    },
  }, {
    path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound,
  },],
},];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL), routes,
});



router.beforeEach((to, from, next) => {
  const user = useUser();

  if (to.meta.requireAuth && !user.isAuthenticated()) {
    return next({ path: "/login" });
  }

  if (to.path === "/login" && localStorage.getItem("token") !== null) {
    return next({ path: "/" });
  }

  return next();

});

export default router;
