<template>
    <div>
        <!-- this is an empty template jus so that the Router can be used -->
    </div>
</template>

<script>
    export default {
        name: "Logout",
        mounted() {
            this.logout()
        },
        methods: {
            logout() {
                //then() is not supported here
                //this.$store.dispatch('logout').then(() => {
                //    //this.$router.push('/');//there is no way to use the router here as it is not yet initialized
                //})
                this.$store.dispatch('logout')
                this.$router.push('/')
            }
        }

    };
</script>