<template>
    <div id="admin-home-container">
        Admin panel
        <div>
            <div id="admin-navigation">
                <div class="admin-nav-link" v-for="link in links">
                    <router-link v-bind:to="link.route" >{{link.name}}</router-link>
                    <!-- <component :is="after_router_link_hook"/> -->
                </div>
                <div>
                    additional menu
                    <keep-alive>
                        <component :is="after_router_link_hook"/>
                    </keep-alive>
                </div>
            </div>

            <div id="admin-view-container">
                <router-view/>
            </div>
        </div>

    </div>


<!--
        <div>
            <div id="admin-navigation">
                <div class="admin-nav-link" v-for="link in links">
                    <router-link
                        v-bind:to="link.route"
                        v-slot="{ href, route, navigate, isActive, isExactActive }"
                    >
                        <a :href="href" @click="navigate">{{link.name}}</a>
                        <component v-if="isActive" :is="after_router_link_hook_render"></component>
                    </router-link>
                </div>
            </div>

            <div id="admin-view-container">
                <router-view/>
            <
        </div>
-->
</template>

<script>
    const aliases = require('@/../components_config/webpack.components.runtime.json')

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        // computed : {
        //     isLoggedIn : function() { return this.$store.getters.isLoggedIn; }
        // },
        // methods: {
        //     logout: function () {
        //         this.$store.dispatch('logout')
        //             .then(() => {
        //                 this.$router.push('/login');
        //             })
        //     }
        // },
        name: "Home",
        mixins: [
            ToastMixin,
        ],
        data() {
            return {
                links: [],
            }
        },
        computed: {
            after_router_link_hook: function () {
                if (typeof this.$route.meta.additional_template !== "undefined") {
                    let additional_template = this.$route.meta.additional_template;
                    for (const alias in aliases) {
                        if (alias !='@' && additional_template.indexOf(alias) != -1) {
                            additional_template = additional_template.replace(alias, aliases[alias]);
                            break;
                        }
                    }
                    //due to the static analisys Webpack does the import must have a path or alias hardcoded
                    //replace the VENDOR path in the
                    additional_template = additional_template.replace(aliases['@VENDOR'], '');
                    additional_template = additional_template.replace('Hook.vue', '');
                    //this causes the whole vendor directory to be searched for...
                    //return () => import(`@VENDOR${hook_name}Hook.vue`) // not working... TODO - fix this
                    return () => import(`@/../../../vendor${additional_template}Hook.vue`)
                }

            }
        },
        created() {
            this.$http.get('/admin-navigation')
                .then(resp => {
                    this.links = resp.data.links;
                })
                .catch(err => {
                    console.log(JSON.stringify( err))
                    console.log(typeof err.config)
                    this.show_toast(err);
                })
                .finally(()=>{
                });
        }
    }
</script>


<style scoped>
    #admin-home-container {

    }
    #admin-navigation {
        border: 1px solid red;
        width: 15%;
        float: left;
    }
    .admin-nav-link {
        display: block;
        with: 200px;
        height: 30px;
        margin: auto;
        border: 1px solid black;
    }
    #admin-view-container {
        width: 85%;
        float: right;
        border: 1px solid green;
    }
    .router-link-active {
        background: yellow;
    }
</style>