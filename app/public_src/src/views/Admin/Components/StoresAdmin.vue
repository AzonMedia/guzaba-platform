<template>
    <div>
        <div>Stores admin</div>

        <ButtonC v-bind:ButtonData="Buttons.AddStore" v-b-modal.create-directory-modal></ButtonC>

        <!-- modals -->
        <AddStoreC v-bind:ModalData="ModalData.AddStore"></AddStoreC>
        <!-- <RemoveStoreC v-bind:ModalData="ModalData.RemoveStore"></RemoveStoreC> -->
    </div>
</template>

<script>
    import ButtonC from '@GuzabaPlatform.Platform/components/Button.vue'
    import AddStoreC from '@GuzabaPlatform.Platform/views/Admin/Components/components/AddStore.vue'
    import RemoveStoreC from '@GuzabaPlatform.Platform/components/GenericModal.vue'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'

    export default {
        name: "StoresAdmin",
        mixins: [
            ToastMixin,
        ],
        components: {
            ButtonC,
            AddStoreC,
            RemoveStoreC,
        },
        data() {
            return {
                Buttons: {
                    AddStore: {
                        label: 'Add Store',
                        is_active: true,
                        handler: this.blank_button_handler,
                    }
                },
                ModalData: {
                    AddStore: {

                    },
                    RemoveStore: {
                        //action_url: ''
                    }
                }
            }
        },
        methods: {
            load_stores() {

            },
            blank_button_handler() {

            },
        }
    }
</script>

<style scoped>

</style>