<template>
      <div>
        <form @submit.prevent="editPost(post.id,edit_post)">
            <div class="form-group" :class="{'has-error' : errors.title.length}">
            <label class="text-success font-weight-bold">Title</label>
            <input type="text" class="form-control bg-primary border-primary text-success" v-model="edit_post.title">
            <p class="help-block text-info" v-for="error in errors.title" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
          </div>
            <div class="form-group" :class="{'has-error' : errors.body.length}">
            <label class="text-success font-weight-bold">Body</label>
            <textarea class="form-control bg-primary border-primary text-success" v-model="edit_post.body"></textarea>
              <p class="help-block text-info" v-for="error in errors.body" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
          </div>
           <loading :active.sync="wait" :can-cancel="true"></loading>
           <button type="submit" class="btn btn-success col-12 mt-2 text-light"><ic icon="edit" size="lg"></ic> <b>Edit your post</b></button>
        </form>
      </div>
</template>
<script>
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.min.css';
  export default{
    props:['post'],
    data(){
      return {
        edit_post:{
          title: null,
          body: null
        },
        errors:{
          title: [],
          body: []
        },
        isall: true,
        wait: false
      }
    },
    components: {
      Loading
    },
    created(){
        this.edit_post.title = this.post.title
        this.edit_post.body = this.post.body
    },
    methods: {
      editPost(id,post){
        var vm = this
        this.wait = true
        this.$http.put('api/posts/'+id,post,{
          headers: {
            Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response) {
            if(response.data.success){
              swal("Update Post",response.data.message,{
              icon:"success"
              })
              vm.post.title = vm.edit_post.title
              vm.post.body = vm.edit_post.body
            }
             vm.wait = false
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
      }
  }
}
</script>
