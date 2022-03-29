import {createApp} from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from "pinia";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import 'bootstrap';
import { library} from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faArrowUpRightFromSquare,
    faBars,
    faExternalLinkAlt,
    faUserLock,
} from "@fortawesome/free-solid-svg-icons";
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

library.add(
    faArrowUpRightFromSquare,
    faBars,
    faExternalLinkAlt,
    faUserLock,
);

createApp(App)
    .use(pinia)
    .use(router)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app');
