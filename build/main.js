import './index.css';
import {createApp} from 'vue';
import App from './App.vue';
import router from './router';
import "@/config/axios.js";
import Markdown from 'vue3-markdown-it';
import 'highlight.js/styles/monokai.css';
import {createPinia} from "pinia";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faArrowUpRightFromSquare, faBars, faExternalLinkAlt, faUser, faUserLock,} from "@fortawesome/free-solid-svg-icons";

library.add(
    faArrowUpRightFromSquare,
    faBars,
    faExternalLinkAlt,
    faUser,
    faUserLock,
);

const pinia = createPinia();

pinia.use(piniaPluginPersistedstate);

createApp(App)
    .use(pinia)
    .use(router)
    .use(Markdown)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app');
