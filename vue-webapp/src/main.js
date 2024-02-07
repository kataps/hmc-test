import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import '@/util/axios';

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
// Import Bootstrap and BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core';

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPenToSquare, faTrashCan } from '@fortawesome/free-regular-svg-icons';
library.add(faPenToSquare, faTrashCan);
/* add font awesome icon component */
Vue.component('font-awesome-icon', FontAwesomeIcon);

// Sweet alert
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
    duration: 2000,
    position: 'top-right', // ['top-right', 'top-center', 'top-left', 'bottom-right', 'bottom-center', 'bottom-left']
    theme: 'outline', // ['toasted-primary', 'outline', 'bubble']
    iconPack: 'material', // ['material', 'fontawesome', 'mdi', 'custom-class', 'callback']
});

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    render: (h) => h(App),
}).$mount('#app');
