<template>
<div>
      <my-errors :msg="msg" ></my-errors>
      <loading  v-if="isloading"></loading>
      <div class=" text-danger">
      <h1 class="font-text-weight">Users that join in this website</h1>
        <div v-for="status in statuses" v-bind:key="status.id">
            <div class="card text-danger border-danger mb-3" style="width:100%;">
            <div class="card-header bg-danger text-light"><h3>{{status.user.first}} {{ status.user.last}}</h3></div>
            <div class="card-body">
                <h5 class="card-title"><b>Few information</b></h5>
                <p class="card-text">Created this user on {{status.user.created_at}}</p>
                <p class="card-text">Email Address {{status.user.email}}</p>
                <p class="card-text">Attitude {{status.report}}</p>
            </div>
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
            this.allSuccess()
        },
        methods : {
            allSuccess(){
                var vm = this
                this.isloading = true
                this.$http.get('api/status/success',{
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
            }
        }
    }
</script>