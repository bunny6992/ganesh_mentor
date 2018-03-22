
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
Vue.use(require('vue-touch'));

Vue.component('side-nav', require("vue-side-nav"));
Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('swot', require('./components/SWOT.vue'));
Vue.component('business-canvas', require('./components/BusinessCanvas.vue'));

var projectResourceComponent = Vue.component('project-resource', require('./components/ProjectResource.vue'));

const app = new Vue({
    el: '#app',
});

// var resizing = false;
// moveNavigation();
// $(window).on('resize', function(){
//    if( !resizing ) {
//       window.requestAnimationFrame(moveNavigation);
//       resizing = true;
//    }
// });

// function moveNavigation(){
//    var mq = checkMQ(); //this function returns mobile,tablet or desktop 

//    if ( mq == 'mobile' && topNavigation.parents('.cd-side-nav').length == 0 ) { //topNavigation = $('.cd-top-nav')
//       detachElements();
//       topNavigation.appendTo(sidebar); //sidebar = $('.cd-side-nav')
//       searchForm.prependTo(sidebar);
//    } else if ( ( mq == 'tablet' || mq == 'desktop') && topNavigation.parents('.cd-side-nav').length > 0 ) {
//       detachElements();
//       searchForm.insertAfter(header.find('.cd-logo')); //header = $('.cd-main-header')
//       topNavigation.appendTo(header.find('.cd-nav'));
//    }
//    resizing = false;
// }

// function detachElements() {
//    topNavigation.detach();//topNavigation = $('.cd-top-nav')
//    searchForm.detach();//searchForm = $('.cd-search')
// }
