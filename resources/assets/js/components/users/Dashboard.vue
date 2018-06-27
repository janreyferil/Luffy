<template>
<div>
    <div v-if="isloading">
    <loading :message="message"></loading>    
    </div>
    <div v-if="!isloading">
        <div class="text-center text-danger">
            <h1 class="font-text-weight">{{message}}</h1>
        </div>
    </div>
</div>
</template>

<script>
import Loading from '../inc/Loading.vue'
     export default {
        data() {
            return {
                user: [],
                message: null,
                msg :{
                    iserror: false,
                    issuccess: false,
                    message: null
                },
                isloading: false,
                message: {
                    title: "Dashboard"
                }
            }
        },
        components: {
            'loading': Loading
        },
        created(){
            this.Users()
        },
        methods : {
            Users(){
                var vm = this
                this.isloading = true
                axios.get('api/home',{
                headers: {
                     Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                    vm.user = response.data.user
                    vm.message = response.data.message
                    vm.isloading = false
                }).catch(function(error){
                    vm.isloading = false
                })
            },
        }
    }
</script>
