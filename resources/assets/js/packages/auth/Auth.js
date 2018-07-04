export default function (Vue) {
    Vue.auth = {
        setToken(token, expiration) {
            localStorage.setItem('token',token)
            localStorage.setItem('expiration',expiration)
        },

        getToken() {
            var token = localStorage.getItem('token');
            var expiration = localStorage.getItem('expiration')

            if(! token || !expiration)
                return null
            if(Date.now() > parseInt(expiration)){
                this.destroyToken();
                return null
            } else {
                return token
            }
        },

        destroyToken() {
            localStorage.removeItem('token')
            localStorage.removeItem('expiration')
            localStorage.removeItem('authenticatedUser')
        },

        isAuthenticated() {
            if(this.getToken())
              return true
            else
            return false
        },

        setAuthenticatedUser(data){
            localStorage.setItem('authenticatedUser',JSON.stringify(data))
        },
        
        getAuthenticatedUser(){
           var authenticatedUser = JSON.parse(localStorage.getItem('authenticatedUser'))
           return authenticatedUser
        },

    }

    Object.defineProperties(Vue.prototype,{
        $auth: {
            get() {
                return Vue.auth
            }
        }
    })
}