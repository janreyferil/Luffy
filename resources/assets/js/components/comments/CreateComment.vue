<template>
<div>
      <div class="row">
          <div class="col-md-8">
              <div class="form-group">
                  <loading :active.sync="wait" :can-cancel="true"></loading>
                  <input type="text" class="form-control bg-primary border-primary text-danger" v-model="comment.body">
                    <p class="help-block text-info" v-for="error in errors.body" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
              </div>
          </div>
          <div class="col-md-4">
                <button @click="createComment(post.id,comment)" class="btn btn-dark mb-4 col-12"><ic icon="comments" size="lg"></ic> Comment</button>
                    <nav v-if="empty" aria-label="Navigation">
                    <ul class="pagination justify-content-end">
                        <li class="page-item" 
                        v-bind:class="[{disabled: !pagination.prev_page_url}]">
                        <a class="page-link" @click="allComments(pagination.prev_page_url)"><ic class="text-secondary" icon="backward" size="1x"></ic></a>
                        </li>

                        <li class="page-item disabled">
                        <a class="page-link text-secondary font-weight-bold">Page {{pagination.current_page}} of {{pagination.last_page}}</a>
                        </li>

                        <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                        <a class="page-link" @click="allComments(pagination.next_page_url)"><ic class="text-secondary" icon="forward" size="1x"></ic></a>
                        </li>
                    </ul>
                 </nav>
          </div>
      </div>
      <div v-for="comment in comments" v-bind:key="comment.id">
          <div class="jumbotron p-1 bg-dark">
            <p class="h6 text-warning"><b>Written On {{comment.created_at}} By {{comment.user_id.first}} {{comment.user_id.last}}</b></p>
            <p>{{comment.body}}</p>
            <ic @click="editComment(comment.id)" class="text-success" icon="edit" size="lg">Edit</ic> <span class="text-success mr-2"><b> Edit</b></span>
             <ic @click="reactComment(post.id,comment.id,true)" icon="thumbs-up" size="lg"></ic> <b>{{comment.react.like}}</b>
             <ic @click="reactComment(post.id,comment.id,false)" icon="thumbs-down" class="text-info ml-2" size="lg"></ic> <span class="text-info"><b>{{comment.react.dislike}}</b></span>
          </div>
      </div>
</div>
</template>

<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.min.css';
    export default {
        props: ['post'],
        data(){
            return {
                comments:[],
                comment: {
                    body: null
                },
                errors:{
                    body: []
                },
                wait: true,
                delay: null,
                data: {
                    react: null
                },
                pagination: {},
                empty: false
            }
        },
        created(){
            this.delay = 10000
            var vm = this
            this.allComments()
           /* setInterval(function(){
            vm.allComments(vm.post.id)
            },this.delay) */
        },
        components: {
            Loading
        },
        methods: {
            clean(){
                this.comment.body = ''
                this.wait = false
            },
            allComments(page_url){
                var vm = this
                page_url = page_url || 'api/posts/'+ this.post.id +'/comments';
                this.$http.get(page_url,{
                headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response){
                    vm.comments = response.data.data
                    this.wait = false
                    if(vm.comments === []){
                        vm.empty = true
                    } else {
                        vm.empty = false
                    }
                    vm.makePagination(response.data.meta,response.data.links);
                })
                .catch(function(error){
                this.wait = false
                console.log(error)
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
            createComment(id,comment){
                    var vm = this
                    this.wait = true
                    this.$http.post('api/posts/'+id+'/comments',comment,{
                    headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                    }
                    })
                    .then(function(response) {
                        if(response.data.success){
                            vm.allComments()
                        }
                        vm.clean()
                    })
                    .catch(function(error) {
                        var data = error.body.errors
                        for(var key in vm.errors){
                            vm.errors[key] = []
                            var errorMessage = data[key]

                            if(errorMessage)
                                vm.errors[key] = errorMessage
                        }
                        vm.wait = false
                    })
            },
            editComment(id){
                var vm = this
                var i = 0
                for(var item in this.comments) {
                    if(this.comments[item].id === id)
                        break;
                    i++;
                }
                swal("Comment Setting","Do you want to edit something?","info", {
                buttons: {
                    cancel: "cancel",
                    catch: {
                    text: "delete",
                    value: "delete",
                    },
                    edit: true,
                },
                })
                .then((value) => {
                switch (value) {
                    case "edit":
                    swal("Edit","Your comment will be update","warning",{
                        content: {
                        element: 'input',
                        attributes: {
                        defaultValue: vm.comments[i].body,
                        }
                        },
                        buttons: true,
                    }).then((val)=>{
                         if(val){
                            swal({
                                title: "Success",
                                text: "You updated your comment",
                                icon: "success",
                                button: "Back",
                            })
                            var data = {
                                body: val
                            }
                            this.updateComment(id,data)
                         }
                        else {
                            swal("Cancel","Your comment edit")
                        }
                    })
                    break;
                    case "delete":
                    swal("Delete!!","Your comment will be delete","error")
                    .then((willDelete)=>{
                         if(willDelete){
                            swal({
                                title: "Delete",
                                text: "You deleted your comment",
                                icon: "success",
                                button: "Back",
                            })
                            this.deleteComment(id)
                        }
                        else {
                            swal("Cancel","Your comment delete")
                        }
                    });
                    break;
                
                    default:
                    swal("Cancel","Your comment edit");
                }
                });
            },
            updateComment(id,data){
                var vm = this
                this.$http.put('api/posts/'+this.post.id+'/comments/'+id,data,{
                    headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                    }
                }).then(function(response){
                        if(response.data.success)
                        vm.allComments()
                }).catch(function(error){
                    console.log(error.data)
                })
            },
            deleteComment(id){
                var vm = this
                this.$http.delete('api/posts/'+this.post.id+'/comments/'+id,{
                    headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                    }
                }).then(function(response){
                    vm.allComments()
                }).catch(function(error){
                    console.log(error.data)
                })
            },
            reactComment(post_id,id,islike){
                var vm = this
                var i = 0
                this.wait = true
                for(var item in this.comments) {
                    if(this.comments[item].id === id)
                        break;
                    i++;
                }
                 if(!islike) {
                this.data.react = 'dislike'
                } else {
                this.data.react = 'like'
                }
                this.$http.post('api/react/posts/'+post_id+'/comments/'+id,vm.data,{
                headers: {
                        Authorization: 'Bearer ' + this.$auth.getToken()
                }
                })
                .then(function(response){
                    vm.allComments()
               // vm.post.react = response.data.react
                })
                .catch(function(error){
                    console.log(error)
                    this.wait = false
               // vm.post.react = response.data.react
                console.log(error)
                })
            }
        }
    }
</script>