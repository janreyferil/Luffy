<template>
<div>
    <div v-if="isloading">
        <loading :message="message"></loading>
    </div>
<div v-if="!isloading">
      <div class=" text-light">
        <h1 v-if="isEmpty" class="font-text-weight text-info">No Reports</h1>
        <h1 v-if="!isEmpty" class="font-text-weight text-info">Reports</h1>
        <div v-if="!isEmpty" v-for="status in statuses" v-bind:key="status.id">
               <div class="card mb-3 bg-dark">
                <h3 class="card-header bg-info border-info">Submitted by {{status.user_report.first}} {{ status.user_report.last}}</h3>
                <div class="card-body">
                    <h5 class="card-title"><b>Report User</b></h5>
                    <h5 class="card-subtitle text-muted">{{status.report_user.first}} {{ status.report_user.last}}</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-dark"><b>Report statement:</b> {{status.report}}</li>
                    <li class="list-group-item bg-dark"><b>Reason statement:</b> {{status.reason}}</li>
                    <li class="list-group-item bg-dark"><b>Level of being bad:</b> {{status.level}}</li>
                </ul>
                <div class="card-footer text-muted bg-primary">
                    <b>Report was submitted on</b> {{status.created_at}}
                </div>
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
                isEmpty:null,
                isloading: false,
                message: {
                    title: 'Report'
                },
            }
        },
        components: {
            'loading': Loading
        },
        created(){
            this.allPending()
        },
        methods : {
            allPending(){
                var vm = this
                this.isloading = true
                this.$http.get('api/reports')
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
                        vm.statuses = response.data.data
                    }
                    vm.isloading = false
                }).catch(function(error){
                    console.log(error)
                    vm.isloading = false
                })
            }
        }
    }
</script>