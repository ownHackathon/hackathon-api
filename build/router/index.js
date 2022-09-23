import {createRouter, createWebHistory} from 'vue-router';
import NotFoundView from '@/views/error/NotFoundView';
import ErrorView from '@/views/error/ErrorView';
import MainLayout from '@/layouts/MainLayout';
import MainView from '@/views/MainView';
import LoginView from '@/views/user/login/LoginView';
import LogoutView from '@/views/user/login/LogoutView';
import PasswordForgottenView from "@/views/user/password/PasswordForgottenView";
import CreateNewPasswordView from "@/views/user/password/CreateNewPasswordView";
import RegisterView from '@/views/user/register/RegisterView';
import UserView from "@/views/user/UserView";
import EventAbout from '@/views/event/EventAbout';
import EventList from '@/views/event/EventList';
import EventEntry from '@/views/event/EventEntry';
import EventCreate from '@/views/event/EventCreate';
import ProjectView from "@/views/ProjectView";
import InvalidTokenView from "@/views/error/InvalidTokenView";
import {useUserStore} from "@/store/UserStore";
import axios from "axios";

const routes = [
  {
    component: MainLayout,
    path: "/",
    children: [
      {
        path: "home",
        name: "home",
        alias: '/',
        component: MainView,
      },
      {
        path: "login",
        name: "login",
        component: LoginView,
      },
      {
        path: "logout",
        name: "logout",
        component: LogoutView,
      },
      {
        path: "register",
        name: "register",
        component: RegisterView,
      },
      {
        path: "user/password/forgotten",
        name: "user_password_forgotten",
        component: PasswordForgottenView,
      },
      {
        path: "user/password/:token",
        name: "user_password_forgotten_token",
        component: CreateNewPasswordView,
      },
      {
        path: "user/:uuid",
        name: "user_view",
        component: UserView,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: "event",
        name: "event_list",
        component: EventList,
      },
      {
        path: "event/information",
        name: "event_general_information",
        component: EventAbout,
      },
      {
        path: "event/:id(\\d+)",
        name: "event_entry",
        component: EventEntry,
      },
      {
        path: "event/:eventName",
        name: "event_entry_named",
        component: EventEntry,
      },
      {
        path: "event/create",
        name: "event_create",
        component: EventCreate,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: "project/:uuid",
        name: "project_entry",
        component: ProjectView,
        meta: {
          requiresAuth: true
        },
      },
      {
        path: "about",
        name: "about",
        component: MainView,
      },
      {
        path: "/error/token/invalid",
        name: "invalid_token",
        component: InvalidTokenView,
      },
    ]
  },
  {
    path: "/discord",
    name: "discord", beforeEnter() {
      location.href = 'https://discord.gg/VjrfCFKRgR';
    },
  },
  {
    path: '/error',
    name: 'error',
    component: ErrorView,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFoundView,
  },
];


const router = createRouter({
  history: createWebHistory(process.env.BASE_URL), routes,
});

/** ToDo: remove Arg next */
router.beforeEach(async (to, from, next) => {

  const userStore = useUserStore();

  if (to.meta.requiresAuth) {
    if (!userStore.user) {
      await axios
          .get('/api/me')
          .then((response) => {
            if (response.status === 200 && response.data.uuid !== undefined) {
              userStore.setUser(response.data);
            } else {
              return next({name: 'login'});
            }
          }).catch(() => {
            userStore.user = null;
            localStorage.removeItem('token');
            return next({name: 'login'});
          });
    }
  }

  if ((to.path === "/login" || to.path === "/register") && localStorage.getItem("token") !== null) {
    return next({name: 'home'});
  }

  return next();
});

export default router;
