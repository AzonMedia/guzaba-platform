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
axios.defaults.baseURL = 'http://localhost:8081/api' //this is used for the API calls
//axios.defaults.baseHost = 'http://localhost:8081/'
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

Vue.config.productionTip = false


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
    //                     new_route.component = () => import('/home/local/PROJECTS/guzaba-platform-skeleton/vendor/guzaba-platform/guzaba-platform/app/public_src/src/views/Crud.vue');
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
