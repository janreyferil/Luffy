
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
import swal from 'sweetalert'



import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(fas)

Vue.use(Auth)
Vue.use(VueRouter)
Vue.use(VueResource)
Vue.component('ic', FontAwesomeIcon)


Vue.http.options.root = 'http://localhost:8000'
Vue.http.headers.common['Accept'] = 'application/json'
Vue.http.headers.common['Content-Type'] = 'application/json'
Vue.http.headers.common['X-CSRF-TOKEN'] =  document.head.querySelector('meta[name="csrf-token"]').content


Vue.http.interceptors.push(function (request, next) {
   // request.headers.set('Authorization', Vue.auth.getToken());
    next(function (response) {
        if (response.status === 401 || response.status === 403) {
           swal("Unauthorized","You are not logged in or do not have the rights to access this site.",{
               icon: "error",
               className: "bg-secondary"
           }).then(function(){
               Router.push('/login')
           })
        } else if(response.status === 422){
            swal("Oppss","Something wrong with your registration!",{
                icon: "info",
                className: "bg-secondary"
            })
        } 
        else if(response.status === 404){
            swal("Page is not found","Incorrect route path.",{
                icon: "error",
                className: "bg-secondary"
            })
        } else if(response.status === 500){
            swal("Internal Error","Contact the admin immediately for this error",{
                icon: "error",
                className: "bg-secondary"
            })
        }
    });
});

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


const app = new Vue({
    el: '#app',
    render: h => h(App),
    router: Router
});



