<template>
    <div id="app">
        <div id="nav">
            <router-link to="/">Home</router-link> |
            <router-link to="/user/login" v-if="! isLoggedIn">Login</router-link> |
            <router-link to="/user/register" v-if="! isLoggedIn">Register</router-link>
            <router-link to="/user/logout" v-if="isLoggedIn">Logout</router-link> |

            <router-link to="/about">About</router-link>
            <router-link to="/admin" v-if="isLoggedIn">Admin</router-link>

            <!-- <router-link v-for="(Link, index) in header_links" :key="index" to="/aa">{{Link.link_name}}</router-link> -->
            <template v-for="(Link, index) in header_links">
                <!-- <router-link :to="Link.link_frontend_location" :target="Link.link_type === 'redirect' ? '_blank' : '_self' ">{{Link.link_name}}</router-link> | -->
                <router-link :to="Link.link_frontend_location">{{Link.link_name}}</router-link> |
            </template>
        </div>
        <router-view/>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                header_links: [],
                footer_links: [],
            }
        },
        computed : {
            isLoggedIn : function() {
                return this.$store.getters.isLoggedIn;
            }
        },
        methods: {
            logout: function () {
                this.$store.dispatch('logout')
                    .then(() => {
                        this.$router.push('/user/login')
                    })
            },
            load_navigation() {
                let url = '/navigation/123';
                this.$http.get(url)
                    .then( resp => {
                        this.header_links = resp.data.links[0].children
                        //console.log(this.header_links)
                        this.footer_links = resp.data.links[1].children
                    })
                    .catch( err => {

                    })
                    .finally( () => {

                    });
            }

        },
        mounted() {
            this.load_navigation()
        }
    }
</script>


<style>
    /*
  #app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }
     */
    #nav {
        padding: 30px;
    }

    #nav a {
        font-weight: bold;
        color: #2c3e50;
    }

    #nav a.router-link-exact-active {
        color: #42b983;
    }
</style>
