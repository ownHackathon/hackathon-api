import {createApp} from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import 'bootstrap';
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import {
    faBars,
    faExternalLinkAlt,
    faUserLock,
} from "@fortawesome/free-solid-svg-icons";

library.add(
    faBars,
    faExternalLinkAlt,
    faUserLock,
);

createApp(App)
    .use(store)
    .use(router)

    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app');
