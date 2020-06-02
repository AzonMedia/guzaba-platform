<template>
    <div>
        <div>Components admin</div>

        <ButtonC v-bind:ButtonData="Buttons.ManageStores"></ButtonC>

        <ButtonC v-bind:ButtonData="Buttons.InstallComponent"></ButtonC>
    </div>
</template>

<script>
    import ButtonC from '@GuzabaPlatform.Platform/components/Button.vue'
    export default {
        name: "ComponentsAdmin",
        components: {
            ButtonC,
        },
        data() {
            return {
                Buttons: {
                    ManageStores: {
                        label: 'Manage Component Stores',
                        is_active: true,
                        handler: this.manage_stores_handler,
                    },
                    InstallComponent: {
                        label: 'Install Component',
                        is_active: true,
                        handler: this.install_component_handler,
                    }
                }
            }
        },
        methods: {
            manage_stores_handler() {
                this.$router.push('/admin/component-stores/')
            },
            install_component_handler() {
                this.$router.push('/admin/components/available')
            }
        }
    }
</script>

<style scoped>

</style>