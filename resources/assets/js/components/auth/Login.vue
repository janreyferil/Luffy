<template>
<div>
<div v-if="isloading">
<loading :message="message"></loading>    
</div>
<div v-if="!isloading">
    <form @submit.prevent="validateBeforeSubmit">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="user-lock" size="lg"></ic> Login</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input name="username" type="email" 
                    v-model="user.email" 
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('username')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('username')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Password</label>
                    <input name="password" type="password" v-model="user.password" 
                    v-validate="'required|min:6|max:20'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('password')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('password')}}</p>
                </div>
                    <button type="submit" class="btn btn-outline-danger col-12">Submit</button>
            </div>     
        </div>
    </form>
    </div>
</div>
</template>
<script>
import Loading from '../inc/Loading.vue'
    export default {
        data() {
            return {
                user:{
                    email : '',
                    password: '',
                },
                isloading: false,
                message: {
                    title: "Login"
                }
            }
        },
        components: {
            'loading': Loading
        },
        methods: {
            clean(){
                // this.user.email = ''
                this.user.password = ''
                this.isloading = false
            },
             validateBeforeSubmit() {
                        let vm = this;
                        //this.$validator.errors.clear();

                        this.$validator.validate().then((result) => {
                                if ( result ) {
                                    vm.login();
                                    return;
                                }
                        })
            },
            login(){
                var vm = this
                this.isloading = true  
                var data = {
                    client_id: 2,
                    client_secret: 'DJxiGkQ9F0vp1XeaUOZrCYbf2dIgJf41lg1DJorK',
                    grant_type: 'password',
                    username: this.user.email,
                    password: this.user.password
                }
                this.$http.post("oauth/token",data)
                .then(function(response) {
                      vm.$auth.setToken(response.data.access_token,response.data.expires_in + Date.now())
                      vm.getUser()
                      vm.$router.push('/dashboard')
                      vm.isloading = false
                })  
                .catch(function(error) {
                    vm.clean()
                })
            },
            getUser(){
                var vm = this
                this.$http.get('api/home')
                .then(function(response) {
                    if(response.data.redirect){
                        vm.isloading = false
                        vm.$auth.destroyToken()
                        swal('Opsss',response.data.message,{
                            icon: "error"
                        }).then(()=>{
                            vm.$router.push('/')
                        })
                    } else {
                        vm.$auth.setAuthenticatedUser(response.data.user)
                        vm.$eventHub.$emit('logged')
                        vm.message = response.data.message
                            }
                        }).catch(function(error){
                            vm.isloading = false
                        })
                    }
        }
    }
</script>
