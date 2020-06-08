<template>
    <div class="store">
        <b-card :title="StoreData.name">
            <img :src="StoreData.logo_url" width="64" style="float: left"/>
            <a :href="StoreData.url">{{StoreData.url}}</a>
            <br />
            <b-button size="sm" variant="outline-danger" v-on:click.stop="" @click="confirm_delete(StoreData)">Remove</b-button>
        </b-card>
    </div>
</template>

<script>

    //import RemoveStoreC from '@GuzabaPlatform.Platform/components/GenericModal.vue'

    export default {
        name: "Store",
        // components: {
        //     RemoveStoreC,
        // },
        props: {
            StoreData : Object
        },
        data() {
            return {
                // RemoveStoreModalData: {
                //
                // }
            }
        },
        methods: {
            confirm_delete(StoreData) {
                //this.$parent.RemoveStore.store_name = StoreData.name;
                //this.$parent.RemoveStore.store_url = StoreData.url;
                this.$parent.ModalData.RemoveStore.title = 'Remove Store'
                this.$parent.ModalData.RemoveStore.text = `Please confirm you would like to remove the Store "${StoreData.name}"?`
                this.$parent.ModalData.RemoveStore.AdditionalData = { store_url: StoreData.url }
                this.$parent.ModalData.RemoveStore.action_url = '/admin/component-store/' + btoa( StoreData.url )
                this.$parent.ModalData.RemoveStore.method = 'delete'
                this.$parent.ModalData.RemoveStore.FinallyCallback = this.$parent.load_stores
                this.$bvModal.show('generic-modal');
            }
        }
    }
</script>

<style scoped>

</style>