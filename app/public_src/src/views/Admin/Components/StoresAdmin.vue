<template>
    <div>
        <div>Stores admin</div>

        <div>
            <ButtonC v-bind:ButtonData="Buttons.AddStore" v-b-modal.add-store-modal></ButtonC>
        </div>
        <div style="clear:both"></div>

        <div class="stores">
            <b-card-group deck>
                <StoreC v-for="(StoreData, index) in stores" v-bind:StoreData="StoreData" v-bind:key="StoreData.url" />
            </b-card-group>
        </div>


        <!-- modals -->
        <AddStoreC v-bind:ModalData="ModalData.AddStore"></AddStoreC>
        <RemoveStoreC v-bind:ModalData="ModalData.RemoveStore"></RemoveStoreC>
    </div>
</template>

<script>
    import ButtonC from '@GuzabaPlatform.Platform/components/Button.vue'
    import AddStoreC from '@GuzabaPlatform.Platform/views/Admin/Components/components/AddStore.vue'
    import RemoveStoreC from '@GuzabaPlatform.Platform/components/GenericModal.vue'
    import StoreC from '@GuzabaPlatform.Platform/views/Admin/Components/components/Store.vue'

    import ToastMixin from '@GuzabaPlatform.Platform/ToastMixin.js'


    export default {
        name: "StoresAdmin",
        mixins: [
            ToastMixin,
        ],
        components: {
            ButtonC,
            StoreC,
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
                    RemoveStore: { //this is a GenericModal
                        title: '',
                        text: '',
                        AdditionalData: {}
                    }
                },
                stores: []
            }
        },
        methods: {
            load_stores() {
                this.$http.get('/admin/component-stores')
                    .then(resp => {
                        this.stores = resp.data.stores;
                    })
                    .catch(err => {
                        this.show_toast(err.response.data.message);
                    });
            },
            blank_button_handler() {

            },
        },
        mounted() {
            this.load_stores()
        }
    }
</script>

<style scoped>

</style>