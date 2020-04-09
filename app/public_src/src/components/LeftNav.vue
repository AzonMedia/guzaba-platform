<template>
    <div class="leftNav">
        LeftNav.vue
        <b-nav vertical class="w-25">
            <!--
            <b-nav-item>
                <b-card no-body class="mb-1">
                    <b-card-header v-b-toggle.crud-classes class="p-1" role="tab">
                        <b-link href="#">Crud Operations</b-link>
                    </b-card-header>

                    <b-collapse id="crud-classes" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <tree-menu class="small" v-for="(node, index) in crud" :nodes="node" :label="index" :contentToLoad="loadCrud" :depth="1"></tree-menu>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </b-nav-item>

            <b-nav-item>
                <b-card no-body class="mb-1">
                    <b-card-header v-b-toggle.crud-permissions class="p-1" role="tab">
                        <b-link href="#">Permissions</b-link>
                    </b-card-header>

                    <b-collapse id="crud-permissions" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <tree-menu class="small" v-for="(node, index) in permissions" :nodes="node" :label="index" :contentToLoad="loadPermissions" :depth="1"></tree-menu>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </b-nav-item>

            <b-nav-item>Example Link</b-nav-item>
            -->
            <b-nav-item  v-for="Link in links" v-bind:key="Link.route">
                <b-card no-body class="mb-1">
                    <b-card-header v-b-toggle.crud-permissions class="p-1" role="tab">
                        <router-link v-bind:to="Link.route">{{Link.name}}</router-link>
                    </b-card-header>
                    <!--
                    <b-collapse id="crud-permissions" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <tree-menu class="small" v-for="(node, index) in permissions" :nodes="node" :label="index" :contentToLoad="loadPermissions" :depth="1"></tree-menu>
                        </b-card-body>
                    </b-collapse>
                    -->
                </b-card>
            </b-nav-item>
        </b-nav>
    </div>
</template>

<script>
// @ is an alias to /src
import Hook from '@GuzabaPlatform.Platform/components/hooks/Hooks.vue'
import TreeMenu from '@GuzabaPlatform.Platform/components/TreeMenu.vue'

export default {
    name: 'leftNav',
    components: {
        Hook,
        TreeMenu
    },
    data() {
        return {
            links: [],
            classes: [],
            controllers: [],
            nonControllers: [],
            permissions: [],
            crud: [],
        }
    },
    mounted() {
        this.$root.$on('bv::collapse::state', (collapseId, isJustShown) => {
            if (isJustShown == true && collapseId == "crud-classes") {
                this.resetData();

                var self = this;

                this.$http.get('/crud-classes')
                .then(resp => {
                    self.crud = resp.data.classes;
                })
                .catch(err => {
                    console.log(err);
                })
            } else if (isJustShown == true && collapseId == "crud-permissions") {
                this.resetData();

                var self = this;

                this.$http.get('/permissions-controllers')
                .then(resp => {
                    self.permissions = resp.data.tree;
                })
                .catch(err => {
                    console.log(err);
                })
            }
        })
    },
    methods: {
        resetData() {
            this.classes = [];
            this.controllers = [];
            this.nonControllers = [];
            this.permissions = [];
            this.crud = [];
        },

        loadCrud(className) {
            this.$emit('loadContent', 'Crud', {selectedClassName: className});
        },

        loadPermissions(methodName) {
            this.$emit('loadContent', 'Permissions', {selectedMethod: methodName});
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

<style>

.nav {
    float: left !important;
    height: 100vh !important;
}

p.card-text, .tree-menu {
    margin-bottom: 5px;
    text-align: left;
}

.nav-link {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.card-header a, .card-text, .nav-link {
    color: #2c3e50;
    text-align: left;
}

.card-text:hover {
    color: #007bff;
}

.card-text {
    padding-left: 1.25rem;
}

.card-text.active {
    color: #000;
    background-color: #eee;
    border-top: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.card-body {
    padding: .5rem 0 .5rem 0 !important;
}

.card:hover {
    color: #2c3e50;
    cursor: auto;
}

.tree-menu .label-wrapper {
    border-bottom: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}
.tree-menu .label-wrapper {
    cursor: pointer !important;
    color: #2c3e50 !important;
}
</style>
