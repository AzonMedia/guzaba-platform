<template>
    <!-- <component :is="get_component_instance"></component> -->
    <component :is="ComponentInstance" v-bind:ObjectData="ObjectData"></component>
</template>

<script>

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        name: 'AliasMatcher',
        mixins: [
            ToastMixin,
//            BaseMixin,
        ],
        data() {
            return {
                //be default points to an empty page
                ObjectData: {
                    page_name: '',
                    page_content: '',
                },
            }
        },
        computed: {
            ComponentInstance() {
                let alias = '';
                if (typeof this.$route.params.pathMatch !== 'undefined') {
                    alias = this.$route.params.pathMatch
                }
                if (!alias) {
                    this.ObjectData = {
                        page_name: 'Not Found',
                        page_content: 'Invalid request',
                    };
                    return () => import('@GuzabaPlatform.Platform/views/NotFound.vue')
                }
                if (typeof this.ObjectData === 'undefined') {
                    this.ObjectData = {
                        page_name: 'Not Found',
                        page_content: `The requested content ${alias} was not found.`
                    };
                    return () => import('@GuzabaPlatform.Platform/views/NotFound.vue')
                }
                if (typeof this.ObjectData.class === 'undefined') {
                    //this is the default when the data is not yet loaded (the class is unknown)
                    return () => import('@GuzabaPlatform.Platform/components/Blank.vue')
                }
                let component = this.get_view_component(this.ObjectData.class)

                if (!component) {
                    let object_class = this.ObjectData.class;
                    this.ObjectData = {
                        page_name: 'Server Error',
                        page_content: `No matching frontend component found for class ${object_class}.`
                    };
                    return () => import('@GuzabaPlatform.Platform/views/ServerError.vue')
                }

                //@GuzabaPlatform.Cms/ViewPage.vue
                //let resolved_component = this.resolve_alias(component);//triggers  GET http://localhost:8081/api/get/@GuzabaPlatform.Cms/ViewPage.vue 404 (Not Found) ????
                let resolved_component = this.resolve_aliased_path(component);
                let vendor_path = this.resolve_aliased_path('@VENDOR/')
                let component_sub_path = resolved_component.replace(vendor_path,'')
                component_sub_path = component_sub_path.replace('.vue','')//remove it and add it to the static import to improve the lookup speed of webpack
                //return () => import(`@/../../../vendor${hook_name}Hook.vue`)//example from Hooks.vue
                //return () => import(component) // webpack does not support loading completely dynamic paths
                //return () => import('@GuzabaPlatform.Cms/ViewPage.vue')//static example
                return () => import(`@/../../../vendor/${component_sub_path}.vue`)

            }
        },
        methods: {
            //because the instance can not be provided immediately
            //and an async operation has to be awaited
            /*
            get_component_instance() {
                let alias = '';
                if (typeof this.$route.params.pathMatch !== "undefined") {
                    alias = this.$route.params.pathMatch
                }
                if (alias) {
                    let object_data = this.resolve_alias();
                }
            },
            */
            /**
             * Returns an array with the object data (including class)
             */
            resolve_alias(alias) {
                let url = this.get_route( 'GuzabaPlatform\\Platform\\Aliases\\Controllers\\Matcher:main', alias );
                this.$http.get( url )
                    .then(resp => {
                        this.ObjectData = resp.data
                    })
                    .catch(err => {
                        //this.show_toast(err);
                        //this.ObjectData.page_title = 'Not found'

                    }).finally(() => {

                    });

            },

            main_process() {
                let alias = '';
                if (typeof this.$route.params.pathMatch !== "undefined") {
                    alias = this.$route.params.pathMatch
                }
                if (alias[0] === '/') {
                    alias = alias.substring(1);
                }
                this.ObjectData = this.resolve_alias(alias)
            }
        },
        // mounted() {
        //     //if (this.$route.params.alias) {
        //     //}
        //     let path = '';
        //     if (typeof this.$route.params.pathMatch !== "undefined") {
        //         path = this.$route.params.pathMatch
        //     }
        //
        // }
        watch: {
            $route (to, from) { // needed because by default no class is loaded and when it is loaded the component for the two routes is the same.
                this.main_process()
            }
        },
        mounted() {
            this.main_process()
        }
    }
</script>
