<template>
  <div>
    <div v-if="create" class="text-danger">
    <h1>Create Post</h1>
    <div class="jumbotron p-2">
      <form @submit.prevent="createPost(post)">
          <div class="form-group">
            <label class="font-weight-bold">Image</label>
            <input type="file" class="form-control bg-primary border-primary text-danger" @change="imageChanged">
          </div>
          <div class="form-group" :class="{'has-error' : errors.title.length}">
            <label class="font-weight-bold">Title</label>
            <input type="text" class="form-control bg-primary border-primary text-danger" v-model="post.title">
            <p class="help-block text-info" v-for="error in errors.title" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
          </div>
          <div class="form-group" :class="{'has-error' : errors.body.length}">
            <label class="font-weight-bold">Body</label>
            <textarea class="form-control bg-primary border-primary text-danger" v-model="post.body"></textarea>
            <p class="help-block text-info" v-for="error in errors.body" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
         </div>
        <loading :active.sync="wait" :can-cancel="true"></loading>
        <button type="submit" class="btn btn-danger col-12"> <ic icon="hand-point-up" size="lg"></ic> <b>Submit</b></button>
      </form>
      <button @click="isCreate(false)" class="btn btn-dark col-12 mt-2"><ic icon="backward"></ic> <b>Back</b></button>
      </div>
     </div>
     <button v-if="!create" @click="isCreate(true)" class="btn btn-danger col-12 mb-2"><ic icon="pencil-alt" size="lg"></ic> <b>Create</b></button>
  </div>
</template>
<script>
import swal from 'sweetalert'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.min.css';
  export default{
    props:{
      posts:{
        required: true
      }
    },
    data(){
      return {
        post:{
          title: '',
          body: '',
          image: ''
        },
        errors:{
          title: [],
          body: []
        },
        wait: false,
        create: false,
      }
    },
    components:{
      Loading
    },
    methods: {
      clean(){
           this.post.title = ''
           this.post.body = ''
           this.errors.title = []
           this.errors.body = []
      },
      imageChanged(e){
        //console.log(e.target)
        var vm = this
        var fileReader = new FileReader()

        fileReader.readAsDataURL(e.target.files[0])

        fileReader.onload = function(e){
          vm.post.image = e.target.result
        }

        console.log(vm.post)
      },
      isCreate(b){
        if(b)
        this.create = true
        else
        this.create = false
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
          console.log(response.data);
          vm.allPosts()
          vm.wait = false
          vm.clean()
          swal("New Post",response.data.message,{
            icon:"success"
          })
          this.create = false
        })
        .catch(function(error) {
            console.log(error);
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
      allPosts(){
        var vm = this
        this.$http.get('api/posts',{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
         vm.$emit('eventname', response.data.data)
        })
        .catch(function(error){
          console.log(error)
        })
      },
  }
}
</script>
