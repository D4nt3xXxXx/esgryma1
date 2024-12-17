/*=========================================================================================
  File Name: main.js
  Description: main vue(js) file
  ----------------------------------------------------------------------------------------
  Item Name: Vuesax Admin - VueJS Dashboard Admin Template
  Author: Pixinvent
  Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import axios from 'axios';
import App from './App.vue'

// Vuesax Component Framework
import Vuesax from 'vuesax'

Vue.use(Vuesax)


// API Calls
import "./http/requests"

// mock
import "./fake-db/index.js"

// Theme Configurations
import '../themeConfig.js'

// Firebase
import '@/firebase/firebaseConfig'


// Auth0 Plugin
import AuthPlugin from "./plugins/auth"

Vue.use(AuthPlugin);


// ACL
import acl from './acl/acl'

// Globally Registered Components
import './globalComponents.js'

// Vue Router
import router from './router'

// Vuex Store
import store from './store/store'

// i18n
import i18n from './i18n/i18n'

// Vuesax Admin Filters
import './filters/filters'

// Clipboard
import VueClipboard from 'vue-clipboard2'

Vue.use(VueClipboard);


// Tour
import VueTour from 'vue-tour'
import api_axios from './api-axios'

Vue.use(VueTour)
require('vue-tour/dist/vue-tour.css')

import Vuetify from 'vuetify'
Vue.use(Vuetify)


// Google Maps
import * as VueGoogleMaps from 'vue2-google-maps'

Vue.use(VueGoogleMaps, {
    load: {
        // Add your API key here
        key: 'AIzaSyB4DDathvvwuwlwnUu7F4Sow3oU22y5T1Y',
        libraries: 'places', // This is required if you use the Auto complete plug-in
    },
})

// Vuejs - Vue wrapper for hammerjs
import {VueHammer} from 'vue2-hammer'

Vue.use(VueHammer);

//IMPORTAMOS EL MIXIN VUE
import mixin from './store/mixin'

Vue.mixin(mixin);

// PrismJS
import 'prismjs'
// import 'prismjs/themes/prism-tomorrow.css'

// Feather font icon
require('@assets/css/iconfont.css')

Vue.config.productionTip = true;
axios.interceptors.request.eject(api_axios);
api_axios();

export default new Vue({
    router,
    store,
    i18n,
    acl,
    render: h => h(App)
}).$mount('#app');
