<template>
  <div>
    <h1>Create Post</h1>
    <form @submit.prevent="createPost(post)">
        <div class="form-group" :class="{'has-error' : errors.title.length}">
        <label>Title</label>
        <input type="text" class="form-control" v-model="post.title">
        <p class="help-block text-primary" v-for="error in errors.title" v-bind:key="error">{{error}}</p>
      </div>
        <div class="form-group" :class="{'has-error' : errors.body.length}">
        <label>Body</label>
        <textarea class="form-control" v-model="post.body"></textarea>
          <p class="help-block text-primary" v-for="error in errors.body" v-bind:key="error">{{error}}</p>
      </div>
      <label v-if="wait">Waiting....</label>
       <button type="submit" class="btn btn-danger col-12">Submit</button>
    </form>
  </div>
</template>
<script>
  export default{
    props:['posts'],
    data(){
      return {
        post:{
          title: null,
          body: null
        },
        errors:{
          title: [],
          body: []
        },
        wait: false
      }
    },
    methods: {
      clean(){
           this.post.title = ''
           this.post.body = ''
           this.errors.title = []
           this.errors.body = []
      },
      createPost(post){
        var vm = this
        this.wait = true
        this.$http.post('api/posts',post,{
          headers: {
            Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response) {
          vm.posts.unshift(response.data.post)
          vm.wait = false
          this.clean()
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
