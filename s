<template>
<div>
<div v-if="isloading">
<loading :message="message"></loading>    
</div>
<div v-if="!isloading">
    <form @submit.prevent="login">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="user-lock" size="lg"></ic> Login</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group" :class="{'has-error' : error.email}">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="email" v-model="user.email" class="border-dark bg-dark text-danger form-control">
                    <p v-if="error.eerror" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{error.email}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : error.password}">
                    <label or="exampleInputPassword1">Password</label>
                    <input type="password" v-model="user.password" class="border-dark bg-dark text-danger form-control">
                    <p v-if="error.perror" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{error.password}}</p>
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
                error: {
                    email : '',
                    password : '',
                    eerror : false,
                    perror : false
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
        created(){
            if(sessionStorage.getItem("login"))
            this.$router.push('/dashboard')
            sessionStorage.removeItem("login")
        },
        methods: {
            login(){
                this.error.email = ''
                this.error.password = ''
                var vm = this
                if(!vm.validate()) {
                    return false;
                 }
                vm.isloading = true  
                var data = {
                    client_id: 2,
                    client_secret: 'DJxiGkQ9F0vp1XeaUOZrCYbf2dIgJf41lg1DJorK',
                    grant_type: 'password',
                    username: this.user.email,
                    password: this.user.password
                }
                this.$http.post("oauth/token",data)
                .then(function(response) {
                      vm.error.eemail = true  
                      vm.error.perror = true
                      vm.$auth.setToken(response.data.access_token,response.data.expires_in + Date.now())
                      sessionStorage.setItem("login",true)
                      vm.getUser()
                })  
                .catch(function(error) {
                    if(error.status == 401){
                        vm.isloading = false  
                        vm.error.eemail = true  
                        vm.error.perror = false
                        var data = error.body.message
                        vm.error.email = data   
                    }
                })
            },
            getUser(){
                var vm = this
                axios.get('api/home',{
                headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                    if(response.data.redirect){
                        vm.isloading = false
                        swal('Opsss',response.data.message,{
                            icon: "error"
                        }).then(()=>{
                            location.reload()
                        })
                        vm.$auth.destroyToken()
                        vm.$router.push('/')
                    } else {
                        console.log(response.data.user)
                        
                        vm.$auth.setAuthenticatedUser(response.data.user)
                        vm.message = response.data.message
                        vm.isloading = false
                            }
                        }).catch(function(error){
                            vm.isloading = false
                        })
                    },
             validate(){
                 var vm = this
                if(vm.user.email == '' && vm.user.password == ''){
                    vm.error.email = 'The email field is required'
                    vm.error.password = 'The password field is required'  
                    vm.error.eerror = true
                    vm.error.perror = true
                    return false 
                } else if(vm.user.email != '' && vm.user.password == '') {
                    vm.error.password = 'The password field is required'  
                    vm.error.eerror = false
                    vm.error.perror = true
                    return false;
                } else if(vm.user.email == '' && vm.user.password != '') {
                    vm.error.eemail = true  
                    vm.error.perror = false
                    vm.error.email = 'The email field is required'
                    return false;
                }
                  return true;
            }
        }
    }
</script>
