<template>
<div>
      <my-errors :msg="msg" ></my-errors>
      <loading  v-if="isloading"></loading>
      <div class=" text-success">
      <h1 class="font-text-weight">Reports Today</h1>
        <div v-for="status in statuses" v-bind:key="status.id">
            <div class="alert alert-dismissible alert-success">
            <h5 class="alert-heading">User who report is {{status.user_report.first}} {{ status.user_report.last}}</h5>
            <hr>
            <h5 class="alert-heading">The reported user is {{status.report_user.first}} {{ status.report_user.last}}</h5>
            <hr>
            <p><b>Report was submitted on</b> {{status.created_at}}</p>
             <hr>
            <p><b>Report statement</b> {{status.report}}</p>
                   <hr>
            <p><b>Reason statement</b> {{status.reason}}</p>
                   <hr>
            <p><b>Level of being bad</b> {{status.level}}</p>
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
                this.$http.get('api/reports',{
                headers: {
                     Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                  //  console.log(response.data)
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