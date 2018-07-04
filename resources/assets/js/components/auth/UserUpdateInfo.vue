<template>
<div>
    <form @submit.prevent="validateBeforeSubmit(user)">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="info-circle" size="lg"></ic> Information Setting</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group">
                    <label for="exampleInputEmail1">First Name</label>
                    <input name="first" type="text" v-model="user.first" 
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('first')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('first')}}</p>
                </div>
                <div class="form-group">
                    <label or="exampleInputPassword1">Last Name</label>
                    <input name="last" type="text" v-model="user.last" 
                    v-validate="'required|max:30'"
                    class="border-dark bg-dark text-danger form-control">
                    <p v-show="errors.has('last')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('last')}}</p>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Change Profile</label>
                    <input type="file" class="form-control-sm bg-dark border-dark  mb-4  text-danger" @change="imageChanged">
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
                    first : '',
                    last: '',
                    profile: '',
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
             imageChanged(e){
                //console.log(e.target)
                var vm = this
                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = function(e){
                vm.user.profile = e.target.result
                }
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
              this.$http.put('api/setting/info',account)
              .then(function(response){
                    vm.wait = false
                  if(response.data.success){
                    vm.$auth.setAuthenticatedUser(response.data.user)
                    swal("Success",response.data.message,{
                        icon: "success"
                    }).then(function(){
                        vm.$eventHub.$emit('logged')
                        vm.$router.push('/dashboard')
                    })
                  } else {
                    vm.wait = false
                    swal("Warning",response.data.message,{
                        icon: "warning"
                    })
                    vm.clean()
                  }
              })
            },
            Users(){
                var vm = this
                this.$http.get('api/home')
                .then(function(response) {
                    vm.user.first = response.data.user.first
                    vm.user.last = response.data.user.last
                }).catch(function(error){
                })
            }
        }
    }
</script>
