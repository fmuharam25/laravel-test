import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'


const AppLayout = () => import('@/components/App.vue')
const Login = () => import('@/components/pages/Login.vue')
const Register = () => import('@/components/pages/Register.vue')
const Dashboard = () => import('@/components/pages/Dashboard.vue')
const UserList = () => import('@/components/pages/UserList.vue')


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: AppLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
            {
                name: "user-list",
                path: '/user-list',
                component: UserList,
                meta: {
                    title: `User Data`
                }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            if(store.state.auth.user.role == 'guest' && to.name == 'user-list'){
                next({ name: "dashboard" })
            }
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router
