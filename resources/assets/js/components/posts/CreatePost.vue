<template>
  <div>
    <div v-if="create" class="text-danger">
    <h1>Create Post</h1>
    <div class="jumbotron p-2">
      <form @submit.prevent="validateBeforeSubmit(post)">
          <div class="form-group">
            <label class="font-weight-bold">Image</label>
            <input type="file" class="form-control bg-primary border-primary text-danger" @change="imageChanged">
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Title</label>
            <input name="title" type="text" v-validate="'required|alpha'" class="border-dark bg-primary text-danger form-control" v-model="post.title">
            <p v-show="errors.has('title')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('title')}}</p>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Body</label>
            <textarea name="body" v-validate="'required'" class="form-control bg-primary border-primary text-danger" v-model="post.body"></textarea>
            <p v-show="errors.has('body')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('body')}}</p>
         </div>
          <loading
            :show="wait"
            :label="'Loading'">
        </loading>
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
import loading from 'vue-full-loading'
//import Loading from 'vue-loading-overlay';
//import 'vue-loading-overlay/dist/vue-loading.min.css';
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
        wait: false,
        create: false,
      }
    },
    components:{
      loading
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
       validateBeforeSubmit(post) {
          let vm = this;
          //this.$validator.errors.clear();

          this.$validator.validate().then((result) => {
                  if ( result ) {
                      vm.createPost(post);
                      return;
                  }
          })
      },
      createPost(post){
        var vm = this
        this.wait = true
        this.$http.post('api/posts',post)
        .then(function(response) {
         if(response.data.success){
          vm.allPosts()
          vm.clean()
          swal("New Post",response.data.message,{
            icon:"success"
          })
          this.create = false
         }
        vm.wait = false
        })
        .catch(function(error) {
          vm.wait = false
        })
      }, 
      allPosts(){
        var vm = this
        this.$http.get('api/posts')
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
