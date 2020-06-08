<template>
    <b-modal :title="ModalData.title" id="generic-modal" @ok="ModalOkHandler">
        {{ModalData.text}}
    </b-modal>
</template>

<script>
    export default {
        name: "GenericModal",
        props: {
            ModalData : Object
        },
        data() {
            return {
            };
        },
        methods: {
            ModalOkHandler(bvModalEvent) {
                let url = this.ModalData.action_url;
                let self = this;
                //let AdditionalData = ModalData.AdditionalData ?? {}
                let AdditionalData = typeof this.ModalData.AdditionalData !== 'undefined' ? this.ModalData.AdditionalData : {}
                //let method = ModalData.method ?? 'patch';
                let method = typeof this.ModalData.method !== 'undefined' ? this.ModalData.method : 'patch';


                this.$http[method](url, AdditionalData).
                then( resp => {
                    this.$parent.show_toast(resp.data.message);
                    if (typeof this.ModalData.SuccessCallback !== 'undefined') {
                        this.ModalData.SuccessCallback();
                    }
                }).catch( err => {
                    this.$parent.show_toast(err.response.data.message);
                    if (typeof this.ModalData.ErrorCallback !== 'undefined') {
                        this.ModalData.ErrorCallback();
                    }
                }).finally( () => {
                    if (typeof this.ModalData.FinallyCallback !== 'undefined') {
                        this.ModalData.FinallyCallback();
                    }
                    this.cleanup()
                });
            },
            cleanup() {
                this.ModalData = {};
            }
        }
    }
</script>

<style scoped>

</style>