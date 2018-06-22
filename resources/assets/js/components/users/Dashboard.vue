<template>
<div>
      <my-errors :msg="msg" ></my-errors>
      <loading  v-if="isloading"></loading>
      <div class="text-center text-danger">
          <h1 class="font-text-weight">{{message}}</h1>
      </div>
</div>

</template>

<script>
import Handle_Message from '../errors/Handle_Message.vue'
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
                isloading: false
            }
        },
        components: {
            'my-errors' : Handle_Message,
            'loading': Loading
        },
        created(){
            this.allUsers()
        },
        methods : {
            allUsers(){
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
