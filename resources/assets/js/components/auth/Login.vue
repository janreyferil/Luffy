<template>
<div>
    <my-errors :msg="msg" ></my-errors>
    <loading  v-if="isloading"></loading>
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><i class="fas fas fa-registered fa-1x"></i>Login</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group" :class="{'has-error' : error.email}">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="email" v-model="user.email" class="border-danger text-danger form-control">
                    <p class="help-block text-primary">{{error.email}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : error.password}">
                    <label or="exampleInputPassword1">Password</label>
                    <input type="password" v-model="user.password" class="border-danger text-danger form-control">
                    <p class="help-block text-primary">{{error.password}}</p>
                </div>
            </div>
            <div class="card-header">
                <button @click="login" class="btn btn-outline-danger col-12">Submit</button>
            </div>
        </div>
    </div>
</template>

<script>
import Handle_Message from '../errors/Handle_Message.vue'
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
                    client_secret: 'sihTlyjrOf0ULA4BAJOZI9RfZbpCGs3LruVrSPT2',
                    grant_type: 'password',
                    username: this.user.email,
                    password: this.user.password
                }
                this.$http.post("oauth/token",data)
                .then(function(response) {
                      vm.$auth.setToken(response.data.access_token,response.data.expires_in + Date.now())
                      vm.$router.push('/dashboard')
                      console.log('Ok')
                })
                .catch(function(error) {
                    if(error.status == 401){
                        vm.isloading = false  
                        var data = error.body.message
                        vm.error.email = data   
                    }
                })
            }, validate(){
                 var vm = this
                if(vm.user.email == '' && vm.user.password == ''){
                    vm.error.email = 'The email field is required'
                    vm.error.password = 'The password field is required'  
                    return false 
                } else if(vm.user.email != '' && vm.user.password == '') {
                    vm.error.password = 'The password field is required'  
                    return false;
                } else if(vm.user.email == '' && vm.user.password != '') {
                    vm.error.email = 'The email field is required'
                    return false;
                }
                  return true;
            }
        }
    }
</script>
