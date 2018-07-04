import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from './components/pages/Home.vue'
import About from './components/pages/About.vue'
import Contact from './components/pages/Contact.vue'
import AdminRegister from './components/auth/AdminRegister.vue'
import UserRegister from './components/auth/UserRegister.vue'
import Login from './components/auth/Login.vue'
import UserUpdate from './components/auth/UserUpdate.vue'
import UserUpdateInfo from './components/auth/UserUpdateInfo.vue'
import Dashboard from './components/users/Dashboard.vue'
import MyPost from './components/users/MyPost.vue'
import StatusPending from './components/admin/StatusPending.vue'
import StatusSuccess from './components/admin/StatusSuccess.vue'
import StatusFailed from './components/admin/StatusFailed.vue'
import Report from './components/admin/Report.vue'
import Posts from './components/posts/Posts.vue'
import NotFound from './components/errors/NotFound.vue'
//import CreatePost from './components/posts/CreatePost.vue'

Vue.use(VueRouter)

const router = new VueRouter({
   linkActiveClass: 'active',
   mode: 'history',
    routes: [
        {
            path:"/",
            component: Home
        },
        {
            path:"/about",
            component: About
        },
        {
            path:"/contact",
            component: Contact
        },
        {
            path:"/login",
            component: Login,
            meta: {
                forVisitors: true
            }
        },
        {
            path:"/register/admin",
            component: AdminRegister,
            meta: {
                forVisitors: true
            }
        },
        {
            path:"/register/user",
            component: UserRegister,
            meta: {
                forVisitors: true
            }
        },
        {
            path:"/dashboard",
            component: Dashboard,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/mypost",
            component: MyPost,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/admin/status/pending",
            component: StatusPending,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/admin/status/success",
            component: StatusSuccess,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/admin/status/failed",
            component: StatusFailed,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/admin/report",
            component: Report,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/posts",
            component: Posts,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/setting/account",
            component: UserUpdate,
            meta: {
                forAuth: true
            }
        },
        {
            path:"/setting/info",
            component: UserUpdateInfo,
            meta: {
                forAuth: true
            }
        },
        {
            path:"*",
            component: NotFound
        }
    ]
})

export default router
