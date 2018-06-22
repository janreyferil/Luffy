<template>
<div>
    <div v-if="msg.iserror">
      <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
            <p class="modal-card-title">Message for you!!</p>
            </header>
            <section class="modal-card-body">
                {{msg.message}}
            </section>
            <footer class="modal-card-foot">
            <button @click="force" class="btn btn-dark">Redirect</button>
            </footer>
        </div>
        </div>
    </div>
    <div v-if="msg.issuccess">
        <div class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
            <p class="modal-card-title">Message for you!!</p>
            <button @click="refresh" class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                {{msg.message}}
            </section>
            <footer class="modal-card-foot">
            <button @click="force" class="button is-danger">Redirect</button>
            <button @click="refresh" class="button">Cancel</button>
            </footer>
        </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        props: ['msg'],
        methods: {
            refresh(){
                var vm = this
                vm.msg.message = null
                vm.msg.iserror = false
                vm.msg.issuccess = false
            },
            force(){
                var vm = this
                vm.msg.message = null
                vm.msg.iserror = false
                vm.msg.issuccess = false
                vm.$router.push(vm.msg.redirect)
            }
        }
    }
</script>
