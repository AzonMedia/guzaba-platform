<template>
    <component :is="ComponentInstance"></component>
</template>

<script>

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'
    import BaseMixin from '@GuzabaPlatform.Platform/BaseMixin.js'

    //const aliases = require('@/../components_config/webpack.components.runtime.json');

    export default {
        name: "ComponentHooks",
        mixins: [
            ToastMixin,
            BaseMixin,
        ],
        props: {
            HookData : Object
        },
        data() {
            return {
                hook_data_ok: false
            }
        },
        computed: {
            ComponentInstance() {
                if (!this.hook_data_ok) {
                    return ''
                }
                let hooked_file = this.resolve_alias(this.HookData.hook_file)
                hooked_file = hooked_file.substring(hooked_file.indexOf('/vendor/') + '/vendor/'.length)
                hooked_file = hooked_file.replace('.vue','')
                hooked_file += '/' + this.HookData.hook_name
                //return () => import(`@/../component_hooks/guzaba-platform/navigation/app/public_src/src/components/AddLink/AfterTabs.vue`)//example hardcoded path
                return () => import(`@/../component_hooks/${hooked_file}.vue`)
            }
        },
        methods: {
            /**
             * Checks is there hook data provided in the bound data
             */
            check_hook_data() {
                let templates = this.get_template_names(this).join('/');
                if (typeof this.HookData.hook_file === "undefined" || !this.HookData.hook_file) {
                    //this.show_toast(`In template ${this.$parent.$options.name} the HookData has no hook_file property defined or it is empty.`);
                    //this.show_toast(`In template ${templates} the HookData has no hook_file property defined or it is empty.`);
                    throw new Error(`In template ${templates} the HookData has no hook_file property defined or it is empty.`);
                }
                if (typeof this.HookData.hook_name === "undefined" || !this.HookData.hook_name) {
                    //this.show_toast(`In template ${this.$parent.$options.name} the HookData has no hook_name property defined or it is empty.`);
                    //this.show_toast(`In template ${templates} the HookData has no hook_name property defined or it is empty.`);
                    throw new Error(`In template ${templates} the HookData has no hook_name property defined or it is empty.`);
                }
            },
        },
        mounted() {
            try {
                this.check_hook_data();
                this.hook_data_ok = true;
            } catch (Error) {
                this.show_toast(Error.message);
            }

        }
    }
</script>

<style scoped>

</style>