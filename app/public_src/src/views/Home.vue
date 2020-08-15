<template>
    <div class="home">
        <!-- <LeftNav v-if="isLoggedIn" v-on:loadContent="loadContent" /> -->
        <!--
        <div style="border: 1px solid red;">
            <LeftNav v-if="isLoggedIn" @loadContent="loadContent" />
        </div>
        -->
        <!--
        <div style="border: 1px solid blue;">
            <component :is="dynamicContent" :contentArgs.sync="contentArgs"></component>
        </div>
        -->
    </div>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from '@GuzabaPlatform.Platform/components/HelloWorld.vue'
//import LeftNav from '@GuzabaPlatform.Platform/components/LeftNav.vue'
//import TreeMenu from '@GuzabaPlatform.Platform/components/TreeMenu.vue'
//import Permissions from '@GuzabaPlatform.Platform/views/Permissions.vue'
//import Crud from '@GuzabaPlatform.Platform/views/Crud.vue'

export default {
    name: 'home',
    components: {
//        HelloWorld,
//        LeftNav,
//        TreeMenu,
//        Permissions,
//        Crud
    },
    data() {
        return {
            // dynamicContent: "HelloWorld",
            // contentArgs: {
            //     msg: "Welcome to Your Vue.js App"
            // }
        }
    },
    computed : {
        isLoggedIn : function() {
            return this.$store.getters.isLoggedIn;
        }
    },
    // methods: {
    //     loadContent(component, vars) {
    //         this.dynamicContent = component;
    //         this.contentArgs = vars;
    //     }
    // }
}
</script>
