// require('./bootstrap');

window.Vue = require('vue');
import vuetify from '../plugins/vuetify'
import axios from 'axios'
import Swal from 'sweetalert2/src/sweetalert2.js'

window.Swal = Swal
window.axios = axios

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('sample', require('./components/Sample.vue').default);


const app = new Vue({
    vuetify,
    el: '#app',
});
