<template>
    <b-modal title="Add Store" id="add-store-modal" @ok="modal_ok_handler" @cancel="modal_cancel_handler" @show="modal_show_handler">
        <div>
            <p>Store URL: <input v-model="store_url" type="text" placeholder="Store URL" /></p>
        </div>
    </b-modal>
</template>

<script>
    export default {
        name: "AddStore",
        props: {
            ModalData : Object
        },
        data() {
            return {
                store_url: '',
            };
        },
        methods: {
            modal_ok_handler(bvModalEvent) {
                let url = '/admin/component-store/';
                let SendValues = {};
                SendValues.store_url = this.store_url;
                this.$http.post(url, SendValues).
                    then( () => {

                    }).catch( err => {
                        this.$parent.show_toast(err.response.data.message);
                    }).finally( () => {
                        this.$parent.load_stores();
                    });
            },
            modal_cancel_handler(bvModalEvent) {
                this.store_url = '';
            },
            modal_show_handler(bvModalEvent) {
                this.store_url = '';
            }
        }
    }
</script>

<style scoped>

</style>