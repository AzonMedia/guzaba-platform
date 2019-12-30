<template>
    <div class="home">
        <LeftNav v-if="isLoggedIn" @loadContent="loadContent" />
        <component :is="dynamicContent" :contentArgs.sync="contentArgs"></component>
    </div>
</template>

<script>
// @ is an alias to /src
//import HelloWorld from '@/GuzabaPlatform/Platform/components/HelloWorld.vue'
//import HelloWorld from '../components/HelloWorld.vue'
import HelloWorld from '@GuzabaPlatform.Platform/components/HelloWorld.vue'
//import LeftNav from '@/GuzabaPlatform/Platform/components/LeftNav.vue'
//import LeftNav from '../components/LeftNav.vue'
import LeftNav from '@GuzabaPlatform.Platform/components/LeftNav.vue'
//import Crud from '@/GuzabaPlatform/Platform/views/Crud.vue'
//import Crud from '../views/Crud.vue'
import Crud from '@GuzabaPlatform.Platform/views/Crud.vue'

export default {
    name: 'home',
    components: {
        HelloWorld,
        LeftNav,
        Crud
    },
    data() {
        return {
            dynamicContent: "HelloWorld",
            contentArgs: {msg: "Welcome to Your Vue.js App"}
        }
    },
    computed : {
        isLoggedIn : function() { return this.$store.getters.isLoggedIn; }
    },
    methods: {
        loadContent(component, vars) {
            this.dynamicContent = component;
            this.contentArgs = vars;
        }
    }
}
</script>
