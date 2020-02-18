/* eslint no-console: ["error", { allow: ["warn", "error"] }] */

export default {
    name: 'ConfigMixin',
    data() {
        return {
            Config: {
                assets_base: 'http://localhost:8081/',
            },
        }
    },
}
