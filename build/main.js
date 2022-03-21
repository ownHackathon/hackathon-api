import {createApp} from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import 'bootstrap';

import { config } from "@fortawesome/fontawesome-svg-core";
import { library, dom } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

config.searchPseudoElements = true;
dom.watch();
import {
    faArrowUpRightFromSquare,
    faBars,
    faExternalLinkAlt,
    faUserLock,
} from "@fortawesome/free-solid-svg-icons";



library.add(
    faArrowUpRightFromSquare,
    faBars,
    faExternalLinkAlt,
    faUserLock,
);


createApp(App)
    .use(store)
    .use(router)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app');
