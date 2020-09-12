<template>
    <!-- nothing to render, just redirect -->
    <!-- <ComponentHookC v-bind:HookData="{ hook_file: '@GuzabaPlatform.Platform/views/Redirect.vue', 'hook_name': 'BeforeRedirect' }"></ComponentHookC> -->
    <div>
        <p v-if="message">{{message}}</p>
        <ComponentHookC v-bind:HookData="{ hook_file: '@GuzabaPlatform.Platform/views/Redirect.vue', 'hook_name': 'BeforeRedirect' }"></ComponentHookC>
    </div>
</template>

<script>

    import ComponentHookC from '@GuzabaPlatform.Platform/components/hooks/ComponentHook.vue'

    /**
     * Renders nothing , just redirects
     */
    export default {
        name: 'Redirect',
        components: {
            ComponentHookC,
        },
        data() {
            return {
                message: '',
            }
        },
        methods: {
            /**
             *
             * @param string to
             */
            resolve_redirect(to) {

                this.$http.get('/redirect/' + to)
                    .then( resp => {
                        if (typeof resp.data.redirect !== 'undefined') {
                            let redirect = resp.data.redirect;
                            if (redirect) {
                                window.location = resp.data.redirect
                            } else {
                                this.message = 'It appears an invalid redirect was followed.'
                            }
                        } else if (typeof resp.data.message !== 'undefined') {
                            this.message = resp.data.message
                        } else {
                            this.message = 'It appears an invalid redirect was followed.'
                        }
                    })
                    .catch( err => {
                        this.message = 'An error occurred while decoding the redirect.'
                    })
            }
        },

        mounted() {
            let to = this.$route.params.to
            this.resolve_redirect(to)
        }
    }
</script>
