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
                then(function(resp) {
                    console.log(resp);
                    console.log(self.$parent.show_toast)
                    self.$parent.show_toast(resp.data.message);
                }).catch(function(err) {
                    self.$parent.show_toast(err.response.data.message);
                }).finally(function(){
                    //self.$parent.get_dir_files(self.ModalData.CurrentDirPath.name);//refresh just in case
                });
            }
        }
    }
</script>

<style scoped>

</style>