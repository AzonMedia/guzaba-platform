/* global process */

import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

import Home from './GuzabaPlatform/Platform/views/Home.vue'
import Auth from './GuzabaPlatform/Platform/views/Auth.vue'

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
                import(
                    /* webpackChunkName: "about" */
                    './GuzabaPlatform/Platform/views/About.vue')
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
        },
        {
            path: '/crud',
            name: 'CRUD',
            component: () =>
                import(
                    './GuzabaPlatform/Platform/views/Crud.vue')
        },

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
