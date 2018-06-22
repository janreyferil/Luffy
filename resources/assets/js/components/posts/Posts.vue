<template>
  <div>
  <div v-if="isloading">
        <loading></loading>
  </div>
  <div v-if="!isloading">
    <my-errors :msg="msg" ></my-errors>
    <div v-if="isall">
      <create-post :posts="posts"></create-post>
      <h1>All Posts</h1>
      <div v-for="post in posts" v-bind:key="post.id">
        <div class="jumbotron p-2">
          <button @click="singlePost(post.id)" type="button" class="btn btn-info col-12">View</button>
          <h2>{{post.title}}</h2>
          <small>Written On {{post.created_at}} By {{post.user.first}} {{post.user.last}}</small>
        </div>
      </div>
    </div>
    <div v-if="!isall">
      <div v-if="isedit" class="jumbotron p-2">
      <edit-post v-if="access" :post=post></edit-post>
      <button v-if="access" @click="deletePost(post.id)" class="btn btn-primary col-12 mt-2">Delete</button>
      <button @click="willEdit(false)" type="button" class="btn btn-secondary col-12 mt-2">Cancel</button>
      </div>
      <div class="jumbotron p-2">
        <div v-if="access">
         <div v-if="!isedit">
            <button @click="willEdit(true)" type="button" class="btn btn-danger col-12 mt-2">Edit your post</button>
          </div>
        </div> 
        <button @click="allPosts" type="button" class="btn btn-secondary col-12 mt-2">Back</button>
        <h3>{{post.title}}</h3>
        <small>Written On {{post.created_at}} By {{post.user.first}} {{post.user.last}}</small>
        <p>{{post.body}}</p>
          <button @click="reactPost(post.id,true)" class="btn badge badge-danger">Like {{post.react.like}}</button>
          <button @click="reactPost(post.id,false)" class="btn badge badge-dark">Dislike {{post.react.dislike}}</button>
        <h5><b>Comments</b></h5>
        <div v-for="comment in comments" v-bind:key="comment.id">
          <p>{{comment.user_id.first}} {{comment.user_id.last}}</p>
          <small>Written On {{comment.created_at}}</small>
          <p>{{comment.body}}</p>
          <span class="btn badge badge-danger">Like {{comment.react.like}}</span>
          <span class="btn badge badge-dark">Dislike {{comment.react.dislike}}</span>
          <hr>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Handle_Message from '../errors/Handle_Message.vue'
import Loading from '../inc/Loading.vue'
import CreatePost from './CreatePost.vue'
import EditPost from './EditPost.vue'
  export default{
    data(){
      return {
        posts:[],
        post:[],
        comments: [],
        access: false,
        isall: true,
        msg :{
            iserror: false,
            issuccess: false,
            message: null
        },
        isloading: false,
        isedit: false,
        data : {
          react : null
        },
      }
    },
    components: {
        'my-errors' : Handle_Message,
        'loading': Loading,
        'create-post': CreatePost,
        'edit-post': EditPost,
    },
    created(){
      this.allPosts()
    },
    methods: {
      clearData(all){
        if(all)
          this.posts = []
        else
          this.post = []
          this.comments = []
      },
      willEdit(edit){
        if(edit)
         this.isedit = true
        else
         this.isedit = false
      },
      allPosts(){
        var vm = this
        this.isloading = true
        this.isedit = false
        this.$http.get('api/posts',{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
          vm.posts = response.data
          this.isall = true
          this.clearData(false)
          this.isloading = false
        })
        .catch(function(error){
          console.log(error)
          vm.isloading = false
        })
      },
      accessControl(id){
        var vm = this
        this.$http.get('api/post/access_control/'+id,{
          headers: {
            Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response) {
          if(!response.data.restrict)
              vm.access = true
         else
           vm.access = false
        })
        .catch(function(error) {

        })
      },
      singlePost(id){
        var vm = this
        vm.accessControl(id)
        this.isloading = true
        vm.allComments(id)
        this.$http.get('api/posts/'+id,{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
          vm.post = response.data
          this.isall = false
          this.clearData(true)
          vm.isloading = false
        })
        .catch(function(error){
          console.log(error)
          vm.isloading = false
        })
      },
      deletePost(id){
        var vm = this
        vm.isloading = true
        this.$http.delete('api/posts/'+id,{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
          console.log(response.data)
          this.allPosts()
        })
        .catch(function(error){
          console.log(error)
          vm.isloading = false
        })
      },
      allComments(id){
        var vm = this
        this.$http.get('api/posts/'+ id +'/comments',{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
          if(response.data.empty){
              vm.comments = []
              return false
          }
          vm.comments = response.data
        })
        .catch(function(error){
          console.log(error)

        })
      },
      reactPost(id,islike){
        var vm = this
        this.data.react = 'like'
        if(!islike)
          this.data.react = 'dislike'
       
        this.$http.post('api/react/posts/'+ id,vm.data,{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
          vm.post.react = response.data.react
        })
        .catch(function(error){
          vm.post.react = response.data.react
          console.log(error)
        })
    
    }
  }
}
</script>
