<template>
<div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4 p-2">
    <div v-if="isuser" class="form-inline">
     <img :src="'storage/profile/'+user.profile" class="avatar float-left">
     <div class="navbar-brand ml-2"> <b>{{user.first}} {{user.last}}</b></div>
    </div>
    <div v-if="!isuser" class="navbar-brand text-success animated infinite fadeOut"><ic icon="gem" size="2x"></ic><b>Emerald</b></div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
      <ul class="navbar-nav mr-auto">
        <li v-if="!isuser" class="nav-item">
          <router-link class="nav-link" to="/"><b><ic icon="home" size="lg" class="mb-1"></ic> Home</b> <span class="sr-only">(current)</span></router-link>
        </li>
        <li v-if="!isuser" class="nav-item">
          <router-link class="nav-link" to="/about"><b><ic icon="info-circle" size="lg" class="mb-1"></ic> About</b></router-link>
        </li>
        <li v-if="!isuser" class="nav-item">
          <router-link class="nav-link" to="/contact"><b><ic icon="phone" size="lg" class="mb-1"></ic> Contact</b></router-link>
        </li>
        <li v-if="isuser" class="nav-item">
          <router-link class="nav-link" to="/dashboard"><b><ic icon="tachometer-alt" size="lg" class="mb-1"></ic> Dashboard</b> <span class="sr-only">(current)</span></router-link>
        </li>
         <li v-if="isuser" class="nav-item">
          <router-link class="nav-link" to="/mypost"><b><ic icon="user" size="lg" class="mb-1"></ic> My Post</b></router-link>
        </li>
        <li v-if="isuser" class="nav-item">
          <router-link class="nav-link" to="/posts"><b><ic icon="users" size="lg" class="mb-1"></ic> Posts</b></router-link>
        </li>
        <li v-if="isadmin" class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b><ic icon="tasks" size="lg" class="mb-1"></ic> Admin Action</b>
          </a>
          <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/admin/status/pending"><b><ic icon="share-square" size="lg"></ic> Pending</b></router-link>
            <router-link class="dropdown-item" to="/admin/status/success"><b><ic icon="users" size="lg"></ic> Users</b></router-link>
            <router-link class="dropdown-item" to="/admin/status/failed"><b><ic icon="times-circle" size="lg"></ic> Failed</b></router-link>
          </div>
        </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-auto">
          <li v-if="!isuser" class="nav-item">
            <router-link class="nav-link" to="/register/user"><b><ic icon="registered" size="lg" class="mt-1"></ic> Register</b> <span class="sr-only">(current)</span></router-link>
          </li>
          <li v-if="!isuser" class="nav-item">
            <router-link class="nav-link" to="/login"><b><ic icon="sign-in-alt" size="lg" class="mt-1"></ic> Login</b> </router-link>
          </li>

        <li v-if="isuser" class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <b><ic icon="user-cog" size="lg" class="mb-1"></ic> User Settings</b>
          </a>
          <div class="dropdown-menu bg-light" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" to="/setting/account"><b><ic icon="cog" size="lg" class="mb-1"></ic> Account Setting</b> </router-link>
            <router-link class="dropdown-item" to="/setting/info"><b><ic icon="cog" size="lg" class="mb-1"></ic> Information Setting</b> </router-link>
          </div>
        </li>
          <li v-if="isuser" class="nav-item">
            <a class="nav-link" @click="logout"><b><ic icon="sign-out-alt" size="lg" class="mb-1"></ic> Logout</b></a>
          </li>
      </ul>
      </form>
    </div>
  </nav>
</div>
</template>

<script>
import swal from 'sweetalert'
    export default {
        data(){
          return {
            isuser: this.$auth.isAuthenticated(),
            isLoading : false,
            isadmin: null,
            user : this.$auth.getAuthenticatedUser(),
            wait: false
          }
        },
        created(){
            var vm = this
            this.$eventHub.$on('logged', function(){
            vm.isuser = this.$auth.isAuthenticated()
            vm.user = this.$auth.getAuthenticatedUser()
            vm.adminControl()
            })
         },
        methods: {
          logout(){
            var vm = this
            swal({
            title: "Are you sure ??",
            text: "Do you want to logout ?", 
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => 
            {
              if (willDelete) {
                swal("You are logged out!", {
                  icon: "success"
                }).then(()=>{
                  this.$auth.destroyToken()
                  sessionStorage.setItem("logout",true)
                  vm.isuser = false
                  vm.isadmin = false
                  vm.$router.push('/login')
                });
                  
              } else {
                swal("Your decision changed",{
                  icon: "info"
                });
              }
            })
              },
               adminControl(){
              var vm = this
                if(!this.isuser) {
                  this.isadmin = false
                } else {
                  this.$http.get('api/check',{
                    headers: {
                      Authorization: 'Bearer ' + this.$auth.getToken()
                    }
                  })
                  .then(function(response){
                      if(response.data.redirect){
                        vm.isadmin = false
                      } else {
                         vm.isadmin = true
                      }
                  })
                }
            }
        }
    }
</script>

<style>
.avatar {
    vertical-align: middle;
    width: 50px;
    height: 50px;
    border-radius: 50%;
}
</style>

