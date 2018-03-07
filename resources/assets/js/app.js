
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import VModal from 'vue-js-modal';
import VueEditor from 'vue2-editor'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueSweetalert2);
Vue.use(VModal, { dynamic: true });
Vue.use(VueEditor);

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('swot', require('./components/SWOT.vue'));
Vue.component('business-canvas', require('./components/BusinessCanvas.vue'));

const app = new Vue({
    el: '#app',
});
