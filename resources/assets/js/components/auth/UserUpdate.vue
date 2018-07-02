<template>
<div>
    <form @submit.prevent="updateSetting(user)">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="cogs" size="lg"></ic> Account Setting</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group" :class="{'has-error' : error.email}">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="email" v-model="user.email" class="border-dark bg-dark text-danger form-control">
                    <p v-if="error.eerror" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{error.email}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Confirm Password</label>
                    <input type="password" v-model="user.password_confirmation" class="border-dark bg-dark text-danger form-control">
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
    <loading :active.sync="wait" :can-cancel="true"></loading>
    </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import swal from 'sweetalert'
    export default {
        data() {
            return {
                user:{
                    email : '',
                    password: '',
                    password_confirmation: '',
                },
                error: {
                    email : '',
                    password : '',
                    eerror : false,
                    perror : false
                },
                isloading: false,
                message: {
                    title: "Update Setting"
                },
                wait : false
            }
        },
        components: {
            Loading
        },
        created(){
            this.Users();
        },
        methods: {
            clean(){
                this.user.password_confirmation = ''
                this.user.password = ''
            },
            updateSetting(account){
              var vm = this
                if(!vm.validate()) {
                    return false;
                 }
              this.wait = true
              this.$http.put('api/setting/account',account,{
                  headers: {
                      Authorization: 'Bearer ' + this.$auth.getToken()
                  }
              })
              .then((response)=>{
                  if(response.data.success){
                    this.wait = false
                    swal("Success",response.data.message,{
                        icon: "success"
                    })
                  } else {
                    this.wait = false
                    swal("Warning",response.data.message,{
                        icon: "warning"
                    })
                    this.clean()
                  }
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
            },
            Users(){
                var vm = this
                this.wait = true
                axios.get('api/home',{
                headers: {
                     Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                    vm.user.email = response.data.user.email
                    vm.wait = false
                    
                }).catch(function(error){
                     vm.wait = false
                })
            }
        }
    }
</script>
