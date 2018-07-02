<template>
      <div>
        <form @submit.prevent="editPost(edit_post)">
          <div class="form-group">
            <label class="text-success font-weight-bold">Title</label>
            <input type="file" class="form-control bg-primary border-primary text-success" @change="imageChanged">
          </div>
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
          title: '',
          body: '',
          image: ''
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
      imageChanged(e){
          var vm = this
          var fileReader = new FileReader()

          fileReader.readAsDataURL(e.target.files[0])

          fileReader.onload = function(e){
          vm.edit_post.image = e.target.result
          }
      },
      editPost(post){
        var vm = this
       // console.log(vm.edit_post.image)
        this.wait = true
        this.$http.put('api/posts/'+this.post.id,post,{
          headers: {
            Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response) {
          console.log(response.data)
            if(response.data.success){
              //console.log(response.data)
              vm.singlePost()
            }
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
        singlePost(){
        var vm = this
        this.$http.get('api/posts/'+this.post.id,{
          headers: {
                 Authorization: 'Bearer ' + this.$auth.getToken()
          }
        })
        .then(function(response){
         vm.$emit('eventnamesingle', response.data)
            swal("Update Post","Update successfully",{
            icon:"success"
            })
         vm.wait = false
        })
        .catch(function(error){
          console.log(error)
        })
      }
  }
}
</script>
