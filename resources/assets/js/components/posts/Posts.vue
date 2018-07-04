<template>
<div>
    <div v-if="isloading">
        <loadinger :message="message"></loadinger>
    </div>
    <div v-if="!isloading">
    <div v-if="isall">
      <create-post @eventname="updateparent" :posts.sync="posts"></create-post>
      <h1 class="text-danger">All Posts</h1>
       <nav aria-label="Navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item" 
                v-bind:class="[{disabled: !pagination.prev_page_url}]">
                <a class="page-link" @click="allPosts(pagination.prev_page_url)"><ic icon="backward" size="1x"></ic></a>
                </li>

                <li class="page-item disabled">
                <a class="page-link text-secondary font-weight-bold">Page {{pagination.current_page}} of {{pagination.last_page}}</a>
                </li>

                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                <a class="page-link" @click="allPosts(pagination.next_page_url)"><ic icon="forward" size="1x"></ic></a>
                </li>
            </ul>
        </nav>
      <div v-for="post in posts" v-bind:key="post.id">
            <div class="jumbotron p-2">
                <button @click="singlePost(post.id)" class="btn btn-primary col-12 mb-2"><ic icon="eye" size="lg" class="text-light"></ic> <ic icon="eye" size="lg" class="text-light ml-2"></ic></button>
                <h3 class="text-center text-danger">{{post.title}}</h3>
                <p class="help-block text-center text-warning">Written On {{post.created_at}} By {{post.user.first}} {{post.user.last}}</p>
            </div>
      </div>
    </div>
    <div v-if="!isall">
      <button @click="allPosts()" class="btn btn-light col-12 mt-2 mb-2"><ic icon="users"></ic> <b>Posts</b></button>
      <div v-if="isedit" class="jumbotron p-2">
      <edit-post @eventnamesingle="updatesingleparent" v-if="access" :post=post></edit-post>
      <button v-if="access" @click="deletePost(post.id)" class="btn btn-info col-12 mt-2 text-light"><ic icon="trash-alt"></ic> <b>Delete</b></button>
      <button @click="willEdit(false)" class="btn btn-dark col-12 mt-2"><ic icon="backward" size="lg"></ic> <b>Back</b></button>
      </div>
        <div v-if="isreport" class="jumbotron p-2">
        <report-user :post="post"></report-user>
      <button @click="willReport(false)" class="btn btn-dark col-12 mt-2"><ic icon="backward"></ic> <b>Back</b></button>
        </div>
        <div v-if="access">
         <div v-if="!isedit">
            <button @click="willEdit(true)" type="button" class="btn btn-success col-12 mb-3 text-light"><ic icon="edit" size="lg"></ic> <b>Edit your post</b></button>
          </div>
        </div> 
        <div v-if="!access">
           <div v-if="!isreport">
            <button @click="willReport(true)" type="button" class="btn btn-warning col-12 mb-3 text-light"><ic icon="exclamation-triangle" size="lg"></ic> <b>Report</b></button>
          </div>
        </div> 
        <div class="jumbotron p-2 text-danger">
        <div class="jumbotron p-2 bg-dark">
        <h2>{{post.title}}</h2>
         <p class="help-block text-warning h6">Written On {{post.created_at}} By {{post.user.first}} {{post.user.last}}</p>
        <img :src="'storage/image/' + post.image" class="img-fluid rounded mx-auto d-block img-thumbnail mb-4" alt="Responsive image">
          <h5>{{post.body}}</h5>
          <ic @click="reactPost(post.id,true)" icon="thumbs-up" size="lg"></ic> <b>{{post.react.like}}</b>
          <ic @click="reactPost(post.id,false)" icon="thumbs-down" class="text-info ml-2" size="lg"></ic> <span class="text-info"><b>{{post.react.dislike}}</b></span>
        </div>
        <h5><b>Comments</b></h5>
        <create-comment :post="post"></create-comment>
      </div>
    </div>
  </div>
        <loading
            :show="wait"
            :label="'Loading'">
        </loading>
</div>
</template>
<script>
import Loadings from '../inc/Loading.vue'
import CreatePost from './CreatePost.vue'
import EditPost from './EditPost.vue'
import swal from 'sweetalert'
import CreateComment from '../comments/CreateComment.vue'
import ReportUser from '../reports/ReportUser.vue'
import loading from 'vue-full-loading'
  export default{
    data(){
      return {
        posts:[],
        post:[],
        access: false,
        isall: true,
        isloading: false,
        isedit: false,
        data : {
          react : null
        },
        message:{
          title: "Post"
        },
        isreport: false,
        pagination: {},
        wait: false
      }
    },
    components: {
        'report-user': ReportUser,
        'loadinger': Loadings,
        'create-post': CreatePost,
        'edit-post': EditPost,
        'create-comment': CreateComment,
        loading
    },
    created(){
      this.allPosts()
      this.data.react = 'like'
      console.log(this.data.react)
    },
    methods: {
      clearData(all){
          this.isreport = false
          this.isedit = false
      },
      willEdit(edit){
        if(edit)
         this.isedit = true
        else
         this.isedit = false
      },
      allPosts(page_url){
        var vm = this
        this.isloading = true
        this.isedit = false
        page_url = page_url || 'api/posts';
        this.$http.get(page_url)
        .then(function(response){
          vm.posts = response.data.data
          vm.makePagination(response.data.meta,response.data.links);
          this.isall = true
          this.clearData(false)
          this.isloading = false
        })
        .catch(function(error){
          console.log(error)
          vm.isloading = false
        })
      },
      makePagination(meta,links) {
          let pagination = {
              current_page: meta.current_page,
              last_page: meta.last_page,
              next_page_url: links.next,
              prev_page_url: links.prev
          }
          this.pagination = pagination;
      },
      accessControl(id){
        var vm = this
        this.$http.get('api/post/access_control/'+id)
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
        this.$http.get('api/posts/'+id)
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
          swal({
            title: "Are you sure ??",
            text: "Do you want to delete your post ?", 
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            swal("Your post is delete!", {
              icon: "success",
            });
             this.$http.delete('api/posts/'+id)
            .then(function(response){
           //   console.log(response.data)
              this.allPosts()
            })
            .catch(function(error){
              console.log(error)
            })
          } else {
            swal("Your decision changed");
                
          }
        })
       
      },
      reactPost(id,islike){
        var vm = this
        this.wait = true
        if(!islike) {
          this.data.react = 'dislike'
        } else {
          this.data.react = 'like'
            islike = false
        }
        this.$http.post('api/react/posts/'+ id,this.data)
        .then(function(response){
          vm.post.react = response.data.react
          vm.wait = false
        })
        .catch(function(error){
          console.log(error)
        })
      },
      updateparent(variable) {
        this.posts = variable
      },
      updatesingleparent(variable) {
        this.post = variable
      },
      willReport(report){
        if(report)
         this.isreport = true
        else
         this.isreport = false
      },
  }
}
</script>
