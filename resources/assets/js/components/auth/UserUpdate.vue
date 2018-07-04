<template>
<div>
    <form @submit.prevent="validateBeforeSubmit(user)">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="cogs" size="lg"></ic> Account Setting</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input name="username" type="email" v-model="user.email"
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('username')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('username')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Confirm Password</label>
                    <input name="password_confirmation" type="password" v-model="user.password_confirmation" 
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('password_confirmation')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('password_confirmation')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Password</label>
                    <input name="password" type="password" v-model="user.password" v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('password')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('password')}}</p>
                </div>
                    <button type="submit" class="btn btn-outline-danger col-12">Submit</button>
            </div>     
        </div>
    </form>
         <loading
            :show="wait"
            :label="'Loading'">
        </loading>
    </div>
</template>
<script>
import loading from 'vue-full-loading'
import swal from 'sweetalert'
    export default {
        data() {
            return {
                user:{
                    email : '',
                    password: '',
                    password_confirmation: '',
                },
                isloading: false,
                message: {
                    title: "Update Setting"
                },
                wait : true
            }
        },
        components: {
            loading
        },
        created(){
            this.Users();
        },
        methods: {
            clean(){
                this.user.password_confirmation = ''
                this.user.password = ''
            },
            validateBeforeSubmit(account) {
                let vm = this;
                //this.$validator.errors.clear();
                this.$validator.validate().then((result) => {
                        if ( result ) {
                            vm.updateSetting(account);
                            return;
                        }
                })
            },
            updateSetting(account){
              var vm = this
              this.wait = true
              this.$http.put('api/setting/account',account)
              .then((response)=>{
                  if(response.data.success){
                    this.wait = false
                    swal("Success",response.data.message,{
                        icon: "success"
                    })
                    vm.$router.push('/dashboard')
                  } else {
                    this.wait = false
                    swal("Warning",response.data.message,{
                        icon: "warning"
                    })
                    this.clean()
                  }
              })
            },
            Users(){
                var vm = this
                this.wait = true
                this.$http.get('api/home')
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
