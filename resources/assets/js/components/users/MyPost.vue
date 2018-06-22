<template>
<div>
      <my-errors :msg="msg" ></my-errors>
      <loading  v-if="isloading"></loading>
      <div class="text-danger">
      <h1 class="text-center font-text-weight">My Posts</h1>
        <div v-for="post in posts" v-bind:key="post.id">
            <div class="jumbotron">
                <h3>{{post.title}}</h3>
                <button class="btn btn-danger">View</button>
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
                posts: [],
                comments: [],
                post: [

                ],
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
            this.myPost()
        },
        methods : {
            myPost(){
                var vm = this
                this.isloading = true
                axios.get('api/user/posts',{
                headers: {
                     Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response) {
                    vm.posts = response.data.post
                    vm.comments = response.data.comment
                    vm.isloading = false
                }).catch(function(error){
                    vm.isloading = false
                })
            },
        }
    }
</script>
