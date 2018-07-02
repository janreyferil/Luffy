<template>
<div>
    <div v-if="isloading">
    <loading :message="message"></loading>    
    </div>
    <div v-if="!isloading">
      <div class="text-danger">
      <h1 class="font-text-weight">My Posts</h1>
        <div v-for="post in posts" v-bind:key="post.id">
            <div class="jumbotron p-2">
                <button class="btn btn-primary col-12 mb-2"><ic icon="eye" size="lg" class="text-light"></ic> <ic icon="eye" size="lg" class="text-light ml-2"></ic></button>
                <h3 class="text-center">{{post.title}}</h3>
            </div>
        </div>
      </div>
</div>
</div>
</template>

<script>
import Loading from '../inc/Loading.vue'
 export default {
        data() {
            return {
                posts: [],
                comments: [],
                post: [

                ],
                isloading: false,
                message: {
                    title: "My Post"
                }
            }
        },
        components: {
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
