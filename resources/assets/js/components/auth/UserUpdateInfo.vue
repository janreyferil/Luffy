<template>
<div>
    <form @submit.prevent="updateSetting(user)">
        <div class="card border-info border-danger text-danger mx-auto mt-4 mb-4" style="max-width:24rem;">
                <div class="card-header bg-danger text-light">
                    <h2 class="font-weight-bold mt-1"><ic icon="info-circle" size="lg"></ic> Information Setting</h2>
                </div>
                <div class="card-body font-weight-bold">
                <div class="form-group" :class="{'has-error' : error.first}">
                    <label for="exampleInputEmail1">First Name</label>
                    <input type="text" v-model="user.first" class="border-dark bg-dark text-danger form-control">
                    <p v-if="error.eerror" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{error.first}}</p>
                </div>
                <div class="form-group" :class="{'has-error' : error.last}">
                    <label or="exampleInputPassword1">Last Name</label>
                    <input type="text" v-model="user.last" class="border-dark bg-dark text-danger form-control">
                    <p v-if="error.perror" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{error.last}}</p>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Change Profile</label>
                    <input type="file" class="form-control-sm bg-dark border-dark  mb-4  text-danger" @change="imageChanged">
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
                    first : '',
                    last: '',
                    profile: '',
                },
                error: {
                    first : '',
                    last: '',
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
             imageChanged(e){
                //console.log(e.target)
                var vm = this
                var fileReader = new FileReader()

                fileReader.readAsDataURL(e.target.files[0])

                fileReader.onload = function(e){
                vm.user.profile = e.target.result
                }
            },
            updateSetting(account){
              var vm = this
                if(!vm.validate()) {
                    return false;
                 }
              this.wait = true
              this.$http.put('api/setting/info',account,{
                  headers: {
                      Authorization: 'Bearer ' + this.$auth.getToken()
                  }
              })
              .then(function(response){
                  if(response.data.success){
                 //     console.log(response.data)
                    vm.wait = false
                    swal("Success",response.data.message,{
                        icon: "success"
                    }).then(function(){
                        location.reload()
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
            validate(){
                 var vm = this
                if(vm.user.first == '' && vm.user.last == ''){
                    vm.error.first = 'The first name field is required'
                    vm.error.last = 'The last name field is required'  
                    vm.error.eerror = true
                    vm.error.perror = true
                    return false 
                } else if(vm.user.first != '' && vm.user.last == '') {
                    vm.error.last = 'The last name field is required'  
                    vm.error.eerror = false
                    vm.error.perror = true
                    return false;
                } else if(vm.user.first == '' && vm.user.last != '') {
                    vm.error.eemail = true  
                    vm.error.perror = false
                    vm.error.first = 'The first name field is required'
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
                    vm.user.first = response.data.user.first
                    vm.user.last = response.data.user.last
                    vm.wait = false
                    
                }).catch(function(error){
                     vm.wait = false
                })
            }
        }
    }
</script>
