<template>
    <div class="footer">
       <div class="text-center">
               <h6 class="text-light"><b>Supported By</b></h6>
                <img :src="icons.html" class="ico-sm" alt="html">           
                <img :src="icons.css" class="ico" alt="css">
                <img :src="icons.js" class="ico" alt="js">
                <img :src="icons.bootstrap" class="ico ml-2" alt="bootstrap">
                <img :src="icons.node" class="ico" alt="node">
                <img :src="icons.git" class="ico" alt="git">
                <img :src="icons.laravel" class="ico-sm ml-2" alt="laravel">
                <img :src="icons.vue" class="ico-sm" alt="vue">
           </div>
       </div>
</template>

<script>
    export default{ 
        data(){
            return {
                icons: {
                    css: 'storage/footer/css.png',
                    js: 'storage/footer/js.png',
                    html: 'storage/footer/html.png',
                    json: 'storage/footer/json.png',
                    bootstrap: 'storage/footer/bootstrap.png',
                    node: 'storage/footer/node.png',
                    git: 'storage/footer/git.png',
                    laravel: 'storage/footer/laravel.png',
                    vue: 'storage/footer/vue.png'
                }
            }
        }
    }
</script>

<style>

html {
  height: 100%;
  box-sizing: border-box;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

body {
  position: relative;
  margin: 0;
  padding-bottom: 5rem;
  min-height: 100%;
  font-family: "Helvetica Neue", Arial, sans-serif;
}

.ico {
    width:24px;
    height: 24px;
}
.ico-sm {
    width:20px;
    height: 20px;
}

.footer {
  position: absolute;
  right: 0;
  bottom: 0;
  left: 0;
  padding: 1rem;
  background-color: darkslategray;
  text-align: center;
}
</style>
