/* eslint no-console: ["error", { allow: ["warn", "error"] }] */

export default {
    name: 'ToastMixin',
    data() {
        return {
            ToastConfig: {
                // title: '',
                autoHideDelay: 5000,
                variant: 'info',
                appendToast: true,
                solid: true,
                noCloseButton: true,
            },
        }
    },
    mounted() {

    },
    methods: {
        show_toast(message, AdditionalConfig) {
            let ToastConfig = JSON.parse(JSON.stringify(this.ToastConfig));
            if (typeof AdditionalConfig !== 'undefined') {
                for (const el in AdditionalConfig) {
                    ToastConfig[el] = AdditionalConfig[el];
                }
            }
            this.$bvToast.toast(message, ToastConfig)
        }
    }
}
