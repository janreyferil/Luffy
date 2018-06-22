<template>
<div>
    <my-errors :msg="msg" ></my-errors>
    <loading  v-if="isloading"></loading>
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><i class="fas fas fa-registered fa-1x"></i>User Registeration</h2>
                </div>
                <div class="card-body font-weight-bold">
                    <div class="form-group" :class="{'has-error' : errors.first.length}">
                        <label for="exampleInputEmail1">First Name</label>
                        <input type="text" v-model="user.first" class="border-danger text-danger form-control">
                        <p class="help-block text-primary" v-for="error in errors.first" v-bind:key="error">{{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.last.length}">
                    <label for="exampleInputEmail1">Last Name</label>
                    <input type="text" v-model="user.last" class="border-danger text-danger form-control">
                    <p class="help-block text-primary" v-for="error in errors.last" v-bind:key="error">{{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.email.length}">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" v-model="user.email" class="border-danger text-danger form-control">
                        <p class="help-block text-primary" v-for="error in errors.email" v-bind:key="error">{{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.password.length}">
                    <label or="exampleInputPassword1">Password</label>
                    <input type="password" v-model="user.password" class="border-danger text-danger form-control">
                        <p class="help-block text-primary" v-for="error in errors.password" v-bind:key="error">{{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.password_confirmation.length}">
                    <label or="exampleInputPassword1">Confirm Password</label>
                    <input type="password" v-model="user.password_confirmation" class="border-danger text-danger form-control">
                    <p class="help-block text-primary" v-for="error in errors.password_confirmation" v-bind:key="error">{{error}}</p>
                </div>
            </div>
            <div class="card-header">
                <button @click="register(user)" class="btn btn-outline-danger col-12">Register</button>
            </div>
        </div>
    </div>
</template>
<script>
import Handle_Message from '../errors/Handle_Message.vue'
import Loading from '../inc/Loading.vue'
     export default {
        data(){
            return {
                user: {
                    first: null,
                    last: null,
                    email: null,
                    password: null,
                    password_confirmation: null
                },
                errors: {
                    first: [],
                    last: [],
                    email: [],
                    password: [],
                    password_confirmation: []
                },
                msg :{
                    iserror: false,
                    issuccess: false,
                    message: null
                },
                isloading: false
            }
        },
        components: {
            'my-errors' : Handle_Message,
            'loading': Loading
        },
        methods: {
            register(user){
                var vm = this
                this.isloading = true
                this.$http.post('api/register/user',user)
                .then(function(response) {
                       if(response.data.success){
                           vm.msg.issuccess = true
                           vm.msg.message= response.data.message
                           vm.msg.redirect = '/login'
                        }
                      vm.isloading = false  
                })
                .catch(function(error) {
                    var data = error.response.data.errors
                    for(var key in vm.errors){
                        vm.errors[key] = []
                        var errorMessage = data[key]
              
                        if(errorMessage)
                            vm.errors[key] = errorMessage
                    }
                       vm.isloading = false
                })
            }
        },
    }
</script>