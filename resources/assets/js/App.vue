<template>
    <div id="id">
        <navbar></navbar>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>

import Navbar from './components/inc/Navbar.vue'
export default {
    components : {
        'navbar' : Navbar
    }
}

</script>
