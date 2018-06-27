<template>
<div>
    <div v-if="isloading">
        <loading :message="message"></loading>
    </div>
    <div v-if="!isloading">

        <div class=" text-light">
        <h1 class="font-text-weight text-danger">All Users</h1>
            <div v-for="status in statuses" v-bind:key="status.id">
                <div class="card mb-3 bg-dark">
                <h3 class="card-header bg-danger border-danger">{{status.user.first}} {{ status.user.last}}</h3>
                <div class="card-body">
                    <h5 class="card-title"><b>Few information</b></h5>
                    <h6 class="card-subtitle text-muted">A college student</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item bg-dark"><b>Email Address:</b> {{status.user.email}}</li>
                    <li class="list-group-item bg-dark"><b>Attitude:</b> {{status.report}}</li>
                </ul>
                <div class="card-footer text-muted bg-primary">
                    {{status.user.created_at}}
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
                isloading: false,
                message: {
                    title: 'Status Success'
                },
            }
        },
        components: {
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
                        swal("Unauthorized","We sended a report to admin because you trying to access the admin page!",{
                        icon: "error"
                        })  
                        this.$router.push('/dashboard')
                    }
                    vm.statuses = response.data.data
                    vm.isloading = false
                }).catch(function(error){
                    vm.isloading = false
                })
            }
        }
    }
</script>