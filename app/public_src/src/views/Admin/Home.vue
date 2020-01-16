<template>
    <div id="admin-home">
        Admin panel
        <div id="admin-navigation">
            <div class="admin-nav-link" v-for="link in links">
                <router-link v-bind:to="link.route" >{{link.name}}</router-link>
            </div>
        </div>

        <div id="admin-page">
            <router-view/>
        </div>

    </div>
</template>

<script>
    export default {
        // computed : {
        //     isLoggedIn : function() { return this.$store.getters.isLoggedIn; }
        // },
        // methods: {
        //     logout: function () {
        //         this.$store.dispatch('logout')
        //             .then(() => {
        //                 this.$router.push('/login');
        //             })
        //     }
        // },
        data() {
            return {
                links: [],
            }
        },
        created() {
            this.$http.get('/admin-navigation')
                .then(resp => {
                    console.log(resp.data.links)
                    this.links = resp.data.links;
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
</script>


<style scoped>
    #admin-home {
        width: 200px;
    }
    #admin-navigation {
        border: 1px solid red;
    }
.admin-nav-link {
    display: block;
    with: 200px;
    height: 30px;
    margin: auto;
    border: 1px solid black;
}
</style>