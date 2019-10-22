import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

import Home from './views/Home.vue'
import Auth from './views/Auth.vue'

Vue.use(Router)

let router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/about',
            name: 'about',
            // route level code-splitting
            // this generates a separate chunk (about.[hash].js) for this route
            // which is lazy-loaded when the route is visited.
            component: () =>
                import(/* webpackChunkName: "about" */ './views/About.vue')
        },
        {
            path: '/login',
            name: 'Login',
            component: Auth
            // component: () => import('@/components/auth/Login.vue'),
            // meta: {
            //     serverPath: 'auth/login'
            // }
        },
        {
            path: '/logout',
            name: 'user logout',
            component: () => store.dispatch('logout')
        }
    ]
})

export default router

router.beforeEach((to, from, next) => {
    //check for auth
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next()
            return
        }
        next('/login')
    } else {
        next()
    }
    //check if need to modify requested url
    if (to.matched.some(record => record.meta.serverPath)) {
        //code goes here
    }
})
