/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import $ from 'jquery';
window.$ = window.jQuery = $;

import VueRouter from 'vue-router';
import Documents from './components/documents/list.vue';
import AddDocument from './components/documents/edit.vue';
import EditDocuments from './components/documents/edit.vue';

window.Vue.use(VueRouter);

import Swal from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Vue.use(Swal);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const routes = [
    {
        path: '/',
        components: {
            Documents: Documents
        },
        name: 'Documents',
        props: {Documents: true}
    },
    {path: '/create', component: AddDocument, name: 'AddDocument', props: true},
    {path: '/edit/:id', component: EditDocuments, name: 'editDocument', props: true},
];

const router = new VueRouter({
    mode: 'history',
    routes
});


const app = new Vue({
    router
}).$mount('#app');
