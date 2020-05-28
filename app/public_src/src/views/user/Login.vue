<template>
    <!-- <div>
        <Hook :name="'beforeRender'"></Hook>
        <b-form inline>
            <label class="sr-only" for="inline-form-input-name">Name</label>
            <b-input id="inline-form-input-name" class="mb-2 mr-sm-2 mb-sm-0" placeholder="Jane Doe"></b-input>

            <label class="sr-only" for="inline-form-input-username">Username</label>
            <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                <b-input id="inline-form-input-username" placeholder="Username"></b-input>
            </b-input-group>

            <b-button variant="primary">Save</b-button>
        </b-form>
        <Hook :name="'afterRender'"></Hook>
        <b-button variant="primary" @click="hodisi()">Dummy btn</b-button>
        <b-button variant="primary" @click="hooks()">Dummy btn hooks</b-button>
    </div> -->
    <div class="container">
        <b-form @submit.stop.prevent="login">

            <b-alert :show="Alert.show" dismissible >{{Alert.message}}</b-alert>

            <label for="username">Username</label>
            <b-input type="text" v-model="username" id="username" required></b-input>

            <label for="text-password">Password</label>
            <b-input type="password" id="text-password" v-model="password" aria-describedby="password-help-block" required></b-input>
            <!-- <div>{{data.password_text}}</div> -->
            <b-form-text id="password-help-block">
            </b-form-text>

            <b-button type="submit" variant="success">Login</b-button>

            <Hook :name="'_after_main'"/>
        </b-form>
    </div>
</template>

<script>
    //import Hook from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
    import Hook from '@GuzabaPlatform.Platform/components/hooks/Hooks.vue'
    import { stringify } from 'qs'
    export default {
        name: "Login",
        components: {
            Hook
        },
        /*methods: {
            hodisi() {
                this.$http.get()
                    .then(resp => {
                        // console.log(resp);
                    })
            },
            hooks() {
                this.$http.get('login')
            },
        },
        mounted() {
            //
            // let old = this.$http.defaults.baseURL = 'http://192.168.0.5:8081/'
            // this.$http.defaults.baseURL = 'http://192.168.0.102/PROJECTS/cookie.php'
            // this.$http.get().then(resp => {
            //     console.log(resp);
            // })
            // this.$http.defaults.baseURL = old

            this.$http.post('login', stringify({ 'username': 'username', 'password': 'password', 'loginform_submit': true }))
                .then(resp => {
                    // console.log(resp.headers.token);
                    let token = resp.headers.token
                    this.$http.defaults.headers.common['Token'] = token
                    localStorage.setItem('user-token', token)

                    let d = new Date();
                    d.setTime(d.getTime() + (1 * 24 * 60 * 60 * 1000));
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = "token=" + token + ";" + expires + ";path=/";
                });

        }*/
        data() {
            return {
                username: null,
                password: null,
                Alert: {
                    show: false,
                    message: '',
                }
            }
        },
        mounted() {
            this.$http.get('/user/login')
        },
        methods: {
            login() {
                let username = this.username;
                let password = this.password;
                let self = this;
                this.$store.dispatch('login', { username, password })
                    .then(
                        //() => this.$router.push('/')
                        resp => {
                            console.log(resp)
                            console.log('aaaaa')
                            let route = resp.data.route || '/'
                            this.$router.push(route)
                        }
                    )
                    //.catch(err => console.log('Not authorized'))
                    .catch(function(err) {
                        self.Alert.message = err.response.data.message
                        self.Alert.show = true
                    })

                this.username = null;
                this.password = null;
            }
        }

    };
</script>