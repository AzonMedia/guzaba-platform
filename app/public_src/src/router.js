/* global process */

import Vue from 'vue'
import Router from 'vue-router'
import store from './store.js'

//import Home from './GuzabaPlatform/Platform/views/Home.vue'
//import Home from '@/GuzabaPlatform/Platform/views/Home.vue'
///import Home from '@GuzabaPlatform.Platform/views/Home.vue'
//import Home from '/home/local/PROJECTS/guzaba-platform-skeleton/guzaba-platform-skeleton/vendor/guzaba-platform/guzaba-platform/app/public_src/src/views/Home.vue'
//import Auth from './GuzabaPlatform/Platform/views/Auth.vue'
//import Auth from '@GuzabaPlatform.Platform/views/Auth.vue'
import Login from '@GuzabaPlatform.Platform/views/user/Login.vue'
import Logout from '@GuzabaPlatform.Platform/views/user/Logout.vue'
import Register from '@GuzabaPlatform.Platform/views/user/Register.vue'
import AliasMatcher from '@GuzabaPlatform.Platform/views/AliasMatcher.vue'
import Redirect from '@GuzabaPlatform.Platform/views/Redirect.vue'
//import NotFound from '@GuzabaPlatform.Platform/views/NotFound.vue'
///import axios from "axios";

//const aliases = require('@/../components_config/webpack.components.runtime.json')
//import aliases from '@/../components_config/webpack.components.runtime.json'

import generated_routes from '@/../components_config/router.config.js'

Vue.use(Router)

// // route level code-splitting
// // this generates a separate chunk (about.[hash].js) for this route
// // which is lazy-loaded when the route is visited.
// component: () =>
//     import(
//         /* webpackChunkName: "about" */
//         //'./GuzabaPlatform/Platform/views/About.vue'
//         '@GuzabaPlatform.Platform/views/About.vue'
//         )


let static_routes = [
    // {
    //     path: '/',
    //     name: 'home',
    //     component: Home
    // },
    // {
    //     path: '/about',
    //     name: 'about',
    //     component: () => import('@GuzabaPlatform.Platform/views/About.vue')
    // },
    // {
    //     path: '/admin',
    //     name: 'admin',
    //     component: () => import('@GuzabaPlatform.Platform/views/Crud/Home.vue')
    // },
    {
        path: '/user/login',
        name: 'Login',
        //component: Auth
        component: Login
        // component: () => import('@/components/auth/Login.vue'),
        // meta: {
        //     serverPath: 'auth/login'
        // }
    },
    {
        path: '/user/logout',
        name: 'user logout',
        // component: () => {
        //     store.dispatch('logout')
        //         .then(() => {
        //         //this.$router.push('/user/login');
        //             this.$router.push('/');
        //         })
        // }
        component: Logout
    },
    {
        path: '/user/register',
        name: 'Register',
        //component: Auth
        component: Register
    },
    {
        path: '/redirect/:to',
        name: 'Redirect',
        //component: Auth
        component: Redirect
    },
    {
        path: '*',//also handles 404
        name: 'AliasMatcher',
        component: AliasMatcher
    },
]

let all_routes = static_routes.concat(generated_routes);
//let all_routes = static_routes;

let router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: all_routes,
})

router.beforeEach((to, from, next) => {
    //check for auth
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (store.getters.isLoggedIn) {
            next()
            return
        }
        next('/user/login')
    } else {
        next()
    }
    //check if need to modify requested url
    if (to.matched.some(record => record.meta.serverPath)) {
        //code goes here
    }
})

export default router