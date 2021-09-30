/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('login', require('./components/Pages/Login.vue').default);
Vue.component('home', require('./components/Pages/Home.vue').default);
Vue.component('brands', require('./components/Pages/Brands.vue').default);
Vue.component('input-component', require('./components/Common/InputContainer.vue').default);
Vue.component('table-component', require('./components/Common/Table.vue').default);
Vue.component('card-component', require('./components/Common/Card.vue').default);
Vue.component('modal-component', require('./components/Common/Modal.vue').default);
Vue.component('alert-component', require('./components/Common/Alert.vue').default);
Vue.component('pagination-component', require('./components/Common/Pagination.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
