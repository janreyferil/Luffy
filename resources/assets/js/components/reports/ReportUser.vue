<template>
    <div class="text-warning">
      <div class="form-group" :class="{'has-error' : errors.report.length}">
            <label><b>Report</b></label>
            <input type="text" v-model="report.report" class="form-control text-light bg-warning border-warning">
             <p class="help-block text-info" v-for="error in errors.report" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
      </div>
        <div class="form-group" :class="{'has-error' : errors.reason.length}">
             <label><b>Reason</b></label>
             <input type="text" v-model="report.reason" class="form-control text-light bg-warning border-warning">
             <p class="help-block text-info" v-for="error in errors.reason" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
        </div>
        <div class="form-group" :class="{'has-error' : errors.level.length}">
            <select v-model="report.level" class="custom-select text-light bg-warning border-warning">
                <option selected="" value="">Level of Report</option>
                <option value="1">One (Mild)</option>
                <option value="2">Two (Fair)</option>
                <option value="3">Three (Harsh)</option>
                <option value="4">Four (Extreamly Harsh)</option>
                <option value="5">Five (Insane)</option>
            </select>
              <p class="help-block text-info" v-for="error in errors.level" v-bind:key="error"><ic icon="exclamation-circle"></ic> {{error}}</p>
        </div>
          <button @click="reportUser(report)"  type="button" class="btn btn-warning col-12 mb-2 text-light"><ic icon="exclamation-triangle" size="lg"></ic> <b>Report</b></button>
    </div>
</template>

<script>
    export default{
        props: ['post'],
        data(){
            return {
                report: {
                    user_to_id:null,
                    report: null,
                    reason: null,
                    level: null
                },
                errors: {
                    report: [],
                    reason: [],
                    level: []
                }
             }
        },
        created(){
            this.report.user_to_id = this.post.user.id
            this.report.level = ''
        },
        methods: {
           reportUser(report){
               var vm = this
                   swal({
                        title: "Report",
                        text: "Do you want to report this user ?", 
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                   })
                   .then((willReport)=>{
                       if(willReport){
                        this.$http.post('api/reports',report,{
                        headers: {
                            Authorization: 'Bearer ' + this.$auth.getToken()
                        }
                        })
                        .then(function(response){
                            swal({
                               title: "Report",
                               text:  response.data.message,
                               icon: "success"
                           })
                        })
                        .catch(function(error){
                             console.log(error)
                            var data = error.body.errors
                            for(var key in vm.errors){
                                vm.errors[key] = []
                                var errorMessage = data[key]

                                if(errorMessage)
                                    vm.errors[key] = errorMessage
                            }
                        })
                       } else {
                           swal("Cancel","You canceld the report",{
                               icon: "info"
                           })
                       }    
                   })
           }
        }
    }
</script>