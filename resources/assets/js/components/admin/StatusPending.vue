<template>
<div>
<div v-if="isloading">
    <loading :message="message"></loading>
</div>
<div v-if="!isloading">
      <div class=" text-danger">
      <h1 v-if="isEmpty" class="font-text-weight">No Pending Request</h1>
      <h1 v-if="!isEmpty" class="font-text-weight">Pending Request</h1>
        <div v-if="!isEmpty" v-for="status in statuses" v-bind:key="status.id">
                <div class="jumbotron p-3">
                    <button @click="updateStatus('failed',status.id)" type="button" class="close text-info"><ic icon="times"></ic></button>
                    <button @click="updateStatus('success',status.id)" type="button" class="close mr-3 text-danger"><ic icon="check"></ic></button>
                    <h5 class="alert-heading">{{status.user.first}} {{ status.user.last}}</h5>
                    <small>Created this user on {{status.user.created_at}}</small>
                </div>
            </div>
            </div>
        </div>
      </div>
</template>

<script>
import Loading from '../inc/Loading.vue'
import swal from 'sweetalert'
 export default {
        data() {
            return {
                statuses: [],
                isloading: false,
                message: {
                    title: 'Status Pending'
                },
                isEmpty:null,
            }
        },
        components: {
            'loading': Loading
        },
        created(){
            this.isloading = true
            this.allPending()
        },
        methods : {
            allPending(){
                var vm = this
                this.$http.get('api/status/pending')
                .then(function(response) {
                    if(response.data.redirect){
                          swal("Unauthorized","We sended a report to admin because you trying to access the admin page!",{
                            icon: "error"
                            })  
                          this.$router.push('/dashboard')
                    }
                    if(response.data.empty){
                        vm.isEmpty = true
                    } else {
                        vm.isEmpty = false   
                    }
                    vm.statuses = response.data.data
                    vm.isloading = false
                }).catch(function(error){
                  //  consle.log(error)
                    vm.isloading = false
                })
            },
            updateStatus(permission,id){  
                var vm = this
                if(permission == 'failed'){
                    var info = {
                        text: "Do you want to reject this user ?",
                        message: "This user was failed in the permission!"
                    }
                }
                else {
                    var info = {
                        text: "Do you want to accept this user?",
                        message: "This user was success in the permission!"
                    }
                }
                swal({
                    title: "Are you sure ??",
                    text: info.text, 
                    icon: "warning",
                    buttons: true,
                    decission: true
                })
                .then((isSuccess) => {
                if (isSuccess) {
                    swal("Permission",info.message, {
                    icon: "success"
                    })
                    var data = {
                            option: 'permission',
                            result: permission
                        }
                        this.$http.put("api/status/"+id, data)
                        .then(function (response) {
                            vm.allPending()
                           // console.log(response.data)
                        }).catch(function (error) {
                            console.log(error)
                        })
                } else {
                    swal("Cancel","Your decision changed",{
                    icon: "info"
                    })  
                }
                })
            }
        }
    }
</script>


       
