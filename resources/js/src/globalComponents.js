/*=========================================================================================
  File Name: globalComponents.js
  Description: Here you can register components globally
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import VxTooltip from './layouts/components/vx-tooltip/VxTooltip.vue'
import VxCard from './components/vx-card/VxCard.vue'
import VxList from './components/vx-list/VxList.vue'
import VxBreadcrumb from './layouts/components/VxBreadcrumb.vue'
import FeatherIcon from './components/FeatherIcon.vue'
import VxInputGroup from './components/vx-input-group/VxInputGroup.vue'
import {Datetime} from 'vue-datetime';
import {Settings} from 'luxon'
import Select2 from 'v-select2-component';
import {ValidationObserver, ValidationProvider, extend, localize} from 'vee-validate';
import VueProgressBar from 'vue-progressbar';

import esVeeValidate from 'vee-validate/dist/locale/es';
import * as rules from 'vee-validate/dist/rules';
import VueSweetalert2 from 'vue-sweetalert2';
import Buton from './views/view/componentes/boton'
import VueTimeago from 'vue-timeago';

// OPCIONES DEL PROGRES BAR
const options = {
    color: 'rgb(6, 159, 236)',
    failedColor: 'red',
    thickness: '3.5px',
    transition: {
        speed: '0.3s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
};

// Opciones para la libreria de tiempos
const optiontimeago = {

    name: 'Timeago', // Component name, `Timeago` by default
    locale: undefined, // Default locale
    locales: {
        'es-ES': require('date-fns/locale/es'),
        'es': require('date-fns/locale/es'),
    }
};
// install rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

localize('es', esVeeValidate);
Settings.defaultLocale = 'es'

Vue.component(VxTooltip.name, VxTooltip)
Vue.component(VxCard.name, VxCard)
Vue.component(VxList.name, VxList)
Vue.component(VxBreadcrumb.name, VxBreadcrumb)
Vue.component(FeatherIcon.name, FeatherIcon)
Vue.component(VxInputGroup.name, VxInputGroup)
Vue.component('datetime', Datetime);
Vue.component('Select2', Select2);
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('boton', Buton);
Vue.use(VueProgressBar, options);
Vue.use(VueTimeago, optiontimeago);

// v-select component
import vSelect from 'vue-select'

// Set the components prop default to return our fresh components
vSelect.props.components.default = () => ({
    Deselect: {
        render: createElement => createElement('feather-icon', {
            props: {
                icon: 'XIcon',
                svgClasses: 'w-4 h-4 mt-1'
            }
        }),
    },
    OpenIndicator: {
        render: createElement => createElement('feather-icon', {
            props: {
                icon: 'ChevronDownIcon',
                svgClasses: 'w-5 h-5'
            }
        }),
    },
});

Vue.component(vSelect)
Vue.use(VueSweetalert2);
