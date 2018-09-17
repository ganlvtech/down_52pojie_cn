import './styles/index.scss';

import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import App from './App.vue';

window.$ = window.jQuery = require('jquery');
window.Popper = require('popper.js');
require('bootstrap');

Vue.config.productionTip = false;
Vue.use(Router);

const router = new Router({
    mode: 'history',
    base: '/',
    routes: [
        {path: '(.*)', name:'home', component: Home}
    ]
});

window.vm = new Vue({
    el: '#app',
    router,
    render: h => h(App)
});
