/* global localStorage Promise axios */

import Vue from 'vue'
import Vuex from 'vuex'
import Axios from 'axios';
//import {stringify} from 'qs';


Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem('user-token') || '',
        user: {}
    },
    mutations: {
        auth_request(state) {
            state.status = 'loading'
        },
        auth_success(state, token, user) {
            state.status = 'success'
            state.token = token
            state.user = user
        },
        auth_error(state) {
            state.status = 'error'
        },
        logout(state) {
            state.status = ''
            state.token = ''
        }
    },
    actions: {
        // register({ commit }, user) {
        //     return new Promise((resolve, reject) => {
        //         commit('auth_request')
        //         Axios.post('user-register', stringify(user))
        //             .then(resp => {
        //                 //TODO automatically login user
        //                 const token = resp.data.message.token
        //                 const user = resp.data.message.user
        //                 localStorage.setItem('user-token', token)
        //                 axios.defaults.headers.common['Token'] = token
        //                 commit('auth_success', token, user)
        //                 resolve(resp)
        //             })
        //             .catch(err => {
        //                 commit('auth_error', err)
        //                 localStorage.removeItem('user-token')
        //                 reject(err)
        //             })
        //     })
        // },
        login({ commit }, user) {

            return new Promise((resolve, reject) => {
                commit('auth_request')
                Axios.post('/user/login', user)
                    .then(resp => {
                        const token = resp.headers.token
                        const user = {}//resp.data.message.user
                        localStorage.setItem('user-token', token)
                        Axios.defaults.headers.common['Token'] = token
                        commit('auth_success', token, user)
                        resolve(resp)
                    })
                    .catch(err => {
                        commit('auth_error')
                        localStorage.removeItem('user-token')
                        reject(err)
                    })
            })
        },
        logout({ commit }) {
            return new Promise(() => {
                commit('logout')
                localStorage.removeItem('user-token');
                delete Axios.defaults.headers.common['Token']
                //resolve();
            })
        }
    },
    getters: {
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status
    }
})
