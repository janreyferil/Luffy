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
                    <div class="form-group">
                        <label for="exampleInputEmail1">First Name</label>
                        <input name="first" type="text" v-model="user.first" v-validate="'required|alpha|max:30'"
                        class="border-dark bg-dark text-danger form-control">
                        <p v-show="errors.has('first')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('first')}}</p>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label>
                    <input name="last" type="text" v-model="user.last" v-validate="'required|alpha|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('last')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('last')}}</p>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input name="username" type="email" v-model="user.email" 
                     class="border-dark bg-dark text-danger form-control"
                     v-validate="'required|max:30'">
                    <p v-show="errors.has('username')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('username')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Password</label>
                    <input name="password" type="password" v-model="user.password"
                    v-validate="'required|max:30'" 
                     class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('password')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('password')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Confirm Password</label>
                    <input name="password_confirmatio" type="password" v-model="user.password_confirmation"
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('password_confirmatio')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('password_confirmatio')}}</p>
                    
                </div>

                 <button @click="validateBeforeSubmit(user)" class="btn btn-outline-danger col-12">Register</button>
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
            validateBeforeSubmit(user) {
                        let vm = this;
                        //this.$validator.errors.clear();

                        this.$validator.validate().then((result) => {
                                if ( result ) {
                                    vm.register(user);
                                    return;
                                }
                        })
            },
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
                       vm.isloading = false
                })
            }
        },
    }
</script>