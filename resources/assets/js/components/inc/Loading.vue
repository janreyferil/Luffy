<template>
    <div>
        <div class="progress">
        <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" v-bind:style="{ width: currWid + '%'}"></div>
        </div>
        <div class="text-center mt-5">
            <h1 class="animated infinite text-danger bounce">Loading</h1>
        </div>
    </div>
</template>
<script>
     export default {
        props:['message'],
        data(){
            return {
                currWid: 0,
            }
        },
        created(){
            var vm = this;
                setInterval(function(){
                vm.currWid += 25
                if(vm.currWid > 101) {
                    vm.currWid = 0
                }
                }, 500);
             }
        }
</script>

<style>

</style>