<template>
    <div>
        <div>Components admin</div>

        <ButtonC v-bind:ButtonData="Buttons.ManageStores"></ButtonC>

        <ButtonC v-bind:ButtonData="Buttons.InstallComponent"></ButtonC>
    </div>
</template>

<script>

    import ButtonC from '@GuzabaPlatform.Platform/components/Button.vue'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        name: "ComponentsAdmin",
        mixins: [
            ToastMixin,
        ],
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
            },
            load_components() {
                this.$http.get('/admin/components')
                    .then(resp => {
                        this.stores = resp.data.stores;
                    })
                    .catch(err => {
                        this.show_toast(err.response.data.message);
                    });
            }
        },
        mounted() {
            this.load_components()
        }
    }
</script>

<style scoped>

</style>