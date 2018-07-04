<template>
      <div>
        <form @submit.prevent="validateBeforeSubmit(post)">
          <div class="form-group">
            <label class="text-success font-weight-bold">Title</label>
            <input type="file" class="form-control bg-primary border-primary text-success" @change="imageChanged">
          </div>
            <div class="form-group">
            <label class="text-success font-weight-bold">Title</label>
            <input name="title" type="text" v-validate="'required|alpha'" class="form-control bg-primary border-primary text-success" v-model="edit_post.title">
            <p v-show="errors.has('title')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('title')}}</p>
          </div>
            <div class="form-group">
            <label class="text-success font-weight-bold">Body</label>
            <textarea name="body" class="form-control bg-primary border-primary text-success" v-validate="'required'" v-model="edit_post.body"></textarea>
            <p v-show="errors.has('body')" class="help-block text-info"><ic icon="exclamation-circle"></ic> {{errors.first('body')}}</p>
          </div>
           <button type="submit" class="btn btn-success col-12 mt-2 text-light"><ic icon="edit" size="lg"></ic> <b>Edit your post</b></button>
        </form>
            <loading
            :show="wait"
            :label="'Loading'">
        </loading>
      </div>
</template>
<script>
import loading from 'vue-full-loading'
  export default{
    props:['post'],
    data(){
      return {
        edit_post:{
          title: '',
          body: '',
          image: ''
        },
        isall: true,
        wait: false
      }
    },
    components: {
      loading
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
        validateBeforeSubmit(post) {
          let vm = this;
          //this.$validator.errors.clear();

          this.$validator.validate().then((result) => {
                  if ( result ) {
                      vm.editPost(post);
                      return;
                  }
          })
      },
      editPost(post){
        var vm = this
       // console.log(vm.edit_post.image)
        this.wait = true
        this.$http.put('api/posts/'+this.post.id,post)
        .then(function(response) {
          console.log(response.data)
            if(response.data.success){
              //console.log(response.data)
              vm.singlePost()
            }
        })
        .catch(function(error) {
              vm.wait = false 
        })
      },
      singlePost(){
        var vm = this
        this.$http.get('api/posts/'+this.post.id)
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
