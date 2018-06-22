
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue'
import VueRouter from 'vue-router'
import Router from './routes.js'
import App from './App.vue'
import Auth from './packages/auth/Auth.js'

import VueResource from 'vue-resource'

Vue.use(Auth)
Vue.use(VueRouter)
Vue.use(VueResource)

Vue.http.options.root = 'http://localhost:8000'
Vue.http.headers.common['Accept'] = 'application/json'
Vue.http.headers.common['Content-Type'] = 'application/json'
Vue.http.headers.common['X-CSRF-TOKEN'] =  document.head.querySelector('meta[name="csrf-token"]').content

/* Vue.http.interceptors.push((request, next)  => {
  // request.headers['Authorization'] = Vue.auth.getToken()
    next((response) => {
      if(response.status == 404 ) {
          Vue.VueRouter.push('/')
      }
    });
  });  */

Router.beforeEach(
    function(to,from,next) {
        if(to.matched.some(record => record.meta.forVisitors)){
            if(Vue.auth.isAuthenticated()){
                next({
                    path: '/dashboard'
                })
            } else next()   
        } 
        else if(to.matched.some(record => record.meta.forAuth)) {
            if(! Vue.auth.isAuthenticated()){
                next({
                    path: '/login'
                })
            } else next()
        } else next()
    }
)

Router.onError(error => {
  console.log(error)  
});

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router: Router
});

