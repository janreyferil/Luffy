<template>
<div>
    <div v-if="isloading">
    <loading :message="message"></loading>    
    </div>
    <div v-if="!isloading">
        <div class="text-center text-danger">
   
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
                user:  [],
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
            this.user = this.$auth.getAuthenticatedUser()
        },
        methods: {

        }
    }
</script>
