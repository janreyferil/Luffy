<template>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4">
  <a class="navbar-brand" href="https://bootswatch.com/cerulean/#">LuffyLand</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarColor02">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <router-link class="nav-link" to="/">Home <span class="sr-only">(current)</span></router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/about">About</router-link>
      </li>
      <li class="nav-item">
         <router-link class="nav-link" to="/contact">Contact</router-link>
      </li>
      <li v-if="isuser" class="nav-item">
         <router-link class="nav-link" to="/posts">Post</router-link>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <ul class="navbar-nav mr-auto">
      <div v-if="!isuser">
        <li class="nav-item">
          <router-link class="nav-link" to="/register/user"><b>Register</b> <span class="sr-only">(current)</span></router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/login"><b>Login as User</b> </router-link>
        </li>
      </div>
      <div v-if="isuser">
        <li class="nav-item">
          <router-link class="nav-link" to="/login"><b>User Setting</b> </router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" @click="logout"><b>Logout</b> <span class="sr-only">(current)</span></a>
        </li>
      </div>
    </ul>
    </form>
  </div>
</nav>
</template>

<script>
    export default {
        data(){
          return {
            isuser: null
          }
        },
        created(){
         this.$auth.isAuthenticated()
        },
        methods: {
          logout(){
            this.$auth.destroyToken()
            this.$router.push('/')
          }
        }
    }
</script>
