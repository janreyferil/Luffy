<template>
<div>
<div v-if="isloading">
<loading :message="message"></loading>    
</div>
<div v-if="!isloading">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="registered" size="lg"></ic> User Registeration</h2>
                </div>
                <div class="card-body font-weight-bold">
                    <div class="form-group" :class="{'has-error' : errors.first.length}">
                        <label for="exampleInputEmail1">First Name</label>
                        <input type="text" v-model="user.first" class="border-dark bg-dark text-danger form-control">
                        <p class="help-block text-info" v-for="error in errors.first" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.last.length}">
                    <label for="exampleInputEmail1">Last Name</label>
                    <input type="text" v-model="user.last" class="border-dark bg-dark text-danger form-control">
                    <p class="help-block text-info" v-for="error in errors.last" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.email.length}">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" v-model="user.email" class="border-dark bg-dark text-danger form-control">
                        <p class="help-block text-info" v-for="error in errors.email" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.password.length}">
                    <label or="exampleInputPassword1">Password</label>
                    <input type="password" v-model="user.password" class="border-dark bg-dark text-danger form-control">
                        <p class="help-block text-info" v-for="error in errors.password" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : errors.password_confirmation.length}">
                    <label or="exampleInputPassword1">Confirm Password</label>
                    <input type="password" v-model="user.password_confirmation" class="border-dark bg-dark text-danger form-control">
                    <p class="help-block text-info" v-for="error in errors.password_confirmation" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
                </div>

                 <button @click="register(user)" class="btn btn-outline-danger col-12">Register</button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import Loading from '../inc/Loading.vue'
import swal from 'sweetalert'
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
                isloading: false,
                message: {
                    title : "Registration"
                }
            }
        },
        components: {
            'loading': Loading
        },
        methods: {
            register(user){
                var vm = this
                this.isloading = true
                this.$http.post('api/register/user',user)
                .then(function(response) {
                    console.log(response.data)
                       if(response.data.success){
                            swal("Success",response.data.message,{
                                icon: "success"
                            })
                        }
                      vm.isloading = false  
                })
                .catch(function(error) {
                    var data = error.body.errors
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