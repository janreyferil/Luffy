<template>
<div>
      <my-errors :msg="msg" ></my-errors>
      <loading  v-if="isloading"></loading>
      <div class=" text-danger">
      <h1 class="font-text-weight">Pending Request</h1>
        <div v-for="status in statuses" v-bind:key="status.id">
            <div class="alert alert-dismissible alert-warning">
            <button @click="updateStatus('failed',status.id)" type="button" class="close" data-dismiss="alert">&Chi;</button>
            <button @click="updateStatus('success',status.id)" type="button" class="close mr-5" data-dismiss="alert">&checkmark;</button>
            <h5 class="alert-heading">{{status.user.first}} {{ status.user.last}}</h5>
            <small>Created this user on {{status.user.created_at}}</small>
            </div>
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
                statuses: [],
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
        created(){
            this.allPending()
        },
        methods : {
            allPending(){
                var vm = this
                this.isloading = true
                this.$http.get('api/status/pending',{
                headers: {
                     Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                    if(response.data.redirect){
                        this.$router.push('/dashboard')
                    }
                    vm.statuses = response.data
                    vm.isloading = false
                }).catch(function(error){
                    vm.isloading = false
                })
            },
            updateStatus(permission,id){
                var vm = this
                this.isloading = true
                  var data = {
                    option: 'permission',
                    result: permission
                }
                this.$http.put("api/status/"+id, data,
                    {
                        headers: {
                            Authorization: 'Bearer ' + this.$auth.getToken()
                        }
                    }
                )
                .then(function (response) {
                    console.log(response.data)
                    vm.isloading = false
                }).catch(function (error) {
                    vm.isloading = false
                });
            }
        }
    }
</script>