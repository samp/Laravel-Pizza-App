import Vue from 'vue'
//import App from './App.vue';
//import VueRouter from 'vue-router';
//import VueAxios from 'vue-axios';
//import axios from 'axios';
//import { routes } from './routes';

//import orderform from './components/OrderForm.vue';
//import cart from './components/Cart.vue';
//import login-popup from './components/LoginPopup.vue';

Vue.component('login-popup', require('./components/LoginPopup.vue').default);

var app = new Vue({
    el: '#app',
    components: {
        'orderform': require('./components/OrderForm.vue').default,
        'cart': require('./components/Cart.vue').default,
        'deals': require('./components/Deals.vue').default,
        'success': require('./components/Success.vue').default,
        'login-popup': require('./components/LoginPopup.vue').default,
    }
 });