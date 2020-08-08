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

            if (typeof message === "object") {
                if (message.constructor.name === "Error") {
                    if (message.response) {
                        message = message.response.data.message;
                    } else {
                        let resource = 'the requiested resource';
                        if (typeof message.config !== 'undefined') {
                            resource = message.config.method + ' ' + message.config.baseURL + message.config.url;
                        }
                        message = `Not allowed to execute ${resource}, the CORS policy not configured or the API server is not running/not accessible.`
                    }
                } else {
                    message = "Unsupported message object of class " + message.constructor.name + " passed to show_toast()";
                }
            } else if (typeof message === "string") {
                //do nothing
            } else {
                message = "Unsupported message of type " + (typeof message) + " passed to show_toast()";
            }

            this.$bvToast.toast(message, ToastConfig);
        },
    }
}
