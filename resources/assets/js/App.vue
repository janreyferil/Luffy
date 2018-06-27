<template>
    <div id="id">
        <navbar></navbar>
        <div class="container">
            <router-view></router-view>
        </div>
        <my-footer></my-footer>
    </div>
</template>

<script>

import Navbar from './components/inc/Navbar.vue'
import Footer from './components/inc/Footer.vue'
export default {
    components : {
        'navbar' : Navbar,
        'my-footer' : Footer
    }
}

</script>
