/* global localStorage Promise */
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

//css start
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
//css end

//axios start
import axios from 'axios'
axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8081/api'
Vue.prototype.$http = axios

import { stringify } from 'qs'
Vue.prototype.$stringify = stringify

const token = localStorage.getItem('user-token')
if (token) {
    Vue.prototype.$http.defaults.headers.common['Token'] = token
}

//axios end

Vue.config.productionTip = false

const vue = new Vue({
    router,
    store,
    render: h => h(App)
})
vue.$mount('#app')

vue.$http.interceptors.response.use(
    response => { return response },
    err => {
        return new Promise(() => {
            if (
                err.status === 401 &&
                err.config &&
                !err.config.__isRetryRequest
            ) {
                vue.$store.dispatch('logout')
            }
            throw err
        })
    }
)
