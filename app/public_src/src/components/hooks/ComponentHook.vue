<template>
    <component :is="get_component_instance"></component>
</template>

<script>

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'
    //import BaseMixin from '@GuzabaPlatform.Platform/BaseMixin.js'

    //const fileExists = require('file-exists');

    //const aliases = require('@/../components_config/webpack.components.runtime.json');

    export default {
        name: "ComponentHook",
        mixins: [
            ToastMixin,
//            BaseMixin,
        ],
        props: {
            HookData : Object
        },
        // data() {
        //     return {
        //         //ComponentInstance: {},//having it as data being initialized on mount is not working either
        //     }
        // },
        /*
        computed: {
            //using computed property instead of a method has the problem of being updated multiple times (this loading the same component multiple times) due to unexpected reasons (parent components updates)
            ComponentInstance() {
                if (!this.hook_data_ok) {
                    return ''
                }
                let hooked_file = this.resolve_alias(this.HookData.hook_file)
                hooked_file = hooked_file.substring(hooked_file.indexOf('/vendor/') + '/vendor/'.length)
                hooked_file = hooked_file.replace('.vue','')
                hooked_file += '/' + this.HookData.hook_name
                //return () => import(`@/../component_hooks/guzaba-platform/navigation/app/public_src/src/components/AddLink/AfterTabs.vue`)//example hardcoded path
                //having the real FS path does not help either as webpack still cant find it
                //let hooked_file_real_path = `@/../component_hooks/${hooked_file}.vue`
                //hooked_file_real_path = this.resolve_alias(hooked_file_real_path)

                //nothing with the 'fs' module works - this is part of Node
                try {
                    //require(hooked_file_real_path) // does not work - doesnt find existing files as it is a full FS path
                    require(`@/../component_hooks/${hooked_file}.vue`) //works because it uses partial path and the webpack preprocessor can locate it
                } catch(err) {
                    //the file does not exist - return nothing
                    return '';
                }
                console.log('loading hook')
                this.hook_loaded = true
                return () => import(`@/../component_hooks/${hooked_file}.vue`)
            }
        },
        */
        methods: {
            /**
             * Checks is there hook data provided in the bound data
             */
            check_hook_data() {
                let components = this.get_parent_component_names().join('/');
                if (typeof this.HookData.hook_file === "undefined" || !this.HookData.hook_file) {
                    throw new Error(`In template ${components} the HookData has no hook_file property defined or it is empty.`);
                }
                if (typeof this.HookData.hook_name === "undefined" || !this.HookData.hook_name) {
                    throw new Error(`In template ${components} the HookData has no hook_name property defined or it is empty.`);
                }
            },
            get_component_instance() {
                try {
                    this.check_hook_data()
                } catch (Error) {
                    this.show_toast(Error.message);
                    return '';
                }

                let hooked_file = this.resolve_alias(this.HookData.hook_file)
                hooked_file = hooked_file.substring(hooked_file.indexOf('/vendor/') + '/vendor/'.length)
                hooked_file = hooked_file.replace('.vue','')
                hooked_file += '/' + this.HookData.hook_name
                //return () => import(`@/../component_hooks/guzaba-platform/navigation/app/public_src/src/components/AddLink/AfterTabs.vue`)//example hardcoded path
                //having the real FS path does not help either as webpack still cant find it
                //let hooked_file_real_path = `@/../component_hooks/${hooked_file}.vue`
                //hooked_file_real_path = this.resolve_alias(hooked_file_real_path)
                //'fs 'module cant be used - it is part of Node
                try {
                    //require(hooked_file_real_path) // does not work - doesnt find existing files as it is a full FS path
                    require(`@/../component_hooks/${hooked_file}.vue`) //works because it uses partial path and the webpack preprocessor can locate it
                } catch (err) {
                    //the file does not exist - return nothing
                    console.log(`No hooked components for ${this.HookData.hook_file}::${this.HookData.hook_name}`)
                    return '';
                }
                console.log(`Loading hooked components in ${hooked_file} for ${this.HookData.hook_file}::${this.HookData.hook_name}`)
                //return () => import(`@/../component_hooks/${hooked_file}.vue`)//the anonymous function works on computed proeprty
                return import(`@/../component_hooks/${hooked_file}.vue`)
            }
        },
    }
</script>

<style scoped>

</style>