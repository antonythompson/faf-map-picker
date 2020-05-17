/**
 * Import everything.
 */
require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import VueRouter from 'vue-router';
import { BootstrapVue } from 'bootstrap-vue'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/en'
import MyPlugin from './util/vue_plugin';

Vue.use(ElementUI, { locale })
Vue.use(MyPlugin);
Vue.use(VueRouter);
Vue.use(BootstrapVue)

Vue.use(require('vue-pusher'), {
    api_key: process.env.MIX_PUSHER_APP_KEY,
    options: {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true,
    }
});
import main from './components/main';
import home from './components/pages/home';
import match from './components/pages/match';
import tournaments from './components/pages/tournaments';
import matches from './components/pages/matches';
import maps from './components/pages/maps';

Vue.component('main-page', main);

/**
 * Routing..
 * @type {*[]}
 */
const routes = [
    {
        name:'home',
        path: '/',
        component: home,
    },
    {
        name:'tournaments',
        path: '/tournaments',
        component: tournaments
    },
    {
        name:'matches',
        path: '/tournaments/:tournament_id/matches',
        component: matches,
        props: (route) => ({tournament_id: route.params.tournament_id })
    },
    {
        name:'match',
        path: '/match/:match_id',
        component: match,
        props: (route) => ({match_id: route.params.match_id })
    },
    {
        name:'maps',
        path: '/maps',
        component: maps
    },
];
const router = new VueRouter({
    mode: 'history',
    routes,
});

/**
 * Setup app
 * @type {Vue | CombinedVueInstance<Vue, object, object, object, Record<never, any>>}
 */
const app = new Vue({
    el: '#app',
    router
});