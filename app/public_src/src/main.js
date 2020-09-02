/* global localStorage Promise */
import Vue from 'vue'

import App from './App.vue'
import router from './router'
import store from './store'

//css start
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
//import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
//Vue.use(IconsPlugin)
//css end

import Fragment from 'vue-fragment'
Vue.use(Fragment.Plugin)

//axios start
import axios from 'axios'
axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8081/api'
Vue.prototype.$http = axios

//TODO - fix the below - it is not working
//Vue.prototype.$aliases = require('@/../components_config/webpack.components.runtime.json')

import { stringify } from 'qs'
Vue.prototype.$stringify = stringify

const token = localStorage.getItem('user-token')
if (token) {
    Vue.prototype.$http.defaults.headers.common['Token'] = token
}

//axios end

//add a global mixin - it will be available in EVERY component
import BaseMixin from '@GuzabaPlatform.Platform/BaseMixin.js'
Vue.mixin(BaseMixin)

//add global style
import '@GuzabaPlatform.Platform/assets/css/global.css'

Vue.config.productionTip = false

Vue.filter('capitalize', function (value) {
    if (!value) {
        return ''
    }
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('humanize', function (value) {
    let fragments = value.split('_');
    for (let i=0; i<fragments.length; i++) {
        fragments[i] = fragments[i].charAt(0).toUpperCase() + fragments[i].slice(1);
    }
    return fragments.join(' ');
})


const vue = new Vue({
    router,
    store,
    render: h => h(App),
    // created () {
    //     this.get_routes('/vue-routes')
    // },
    // methods: {
    //     get_routes(url) {
    //         axios
    //             .get(url)
    //             .then(function(response) {
    //                 response.data.routes.forEach(function(route) {
    //                     //console.log(route);
    //                     let new_route = {
    //                        path: route.path,
    //                        name: route.name,
    //                     };
    //                     if (route.component.indexOf('@') == 0) {
    //                        let component_path = route.component;
    //                        for (const alias in aliases) {
    //                            if (alias !='@' && component_path.indexOf(alias) != -1) {
    //                                component_path = component_path.replace(alias, aliases[alias]);
    //                                break;
    //                            }
    //                        }
    //                        //console.log(component_path);
    //                        component_path = component_path.replace(aliases['@VENDOR'], '');
    //                        component_path = component_path.replace('.vue', '');
    //                         //console.log(component_path);
    //                        ///new_route.component = () => import(`@/../../../vendor${component_path}.vue`)
    //                     } else {
    //                         //this is unusual and would mean that the component is already required which will not be the case for the modules (unless a component is reused)
    //                        ///new_route.component = route.component;
    //                     }
    //                     new_route.component = () => import('/home/local/PROJECTS/guzaba-platform-skeleton/vendor/guzaba-platform/guzaba-platform/app/public_src/src/views/CrudAdmin.vue');
    //                     console.log('==================');
    //                     console.log(new_route);
    //                     this.$router.addRoutes([new_route]);
    //                 });
    //             })
    //             //.catch(err => console.log(err))
    //             .catch(function(){})
    //     }
    // }
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
