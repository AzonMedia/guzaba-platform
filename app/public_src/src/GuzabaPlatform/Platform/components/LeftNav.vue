<template>
    <div class="leftNav">
        <b-nav vertical class="w-25">
            <b-nav-item>
                <b-card no-body class="mb-1">
                    <b-card-header v-b-toggle.crud-classes class="p-1" role="tab">
                        <b-link href="#">Crud Operations</b-link>
                    </b-card-header>

                    <b-collapse id="crud-classes" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text v-for="classFullName in classes" @click="loadCrud(classFullName)" class="small" :class="active[classFullName]"> {{ classFullName }} </b-card-text>
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
                            <tree-menu @loadPermissions="loadPermissions" class="small" v-for="(node, index) in tree" :nodes="node" :label="index" :depth="1"></tree-menu>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </b-nav-item>

            <b-nav-item>Example Link</b-nav-item>
        </b-nav>
    </div>
</template>

<script>
// @ is an alias to /src
import Hook from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
import TreeMenu from '@/GuzabaPlatform/Platform/components/TreeMenu.vue'

export default {
    name: 'leftNav',
    components: {
        Hook,
        TreeMenu
    },
    data() {
        return {
            classes: [],
            controllers: {},
            nonControllers: [],
            active: [],
            tree: []
        }
    },
    mounted() {
        this.$root.$on('bv::collapse::state', (collapseId, isJustShown) => {
            if (isJustShown == true && collapseId == "crud-classes") {
                this.resetData();

                var self = this;

                this.$http.get('/crud-classes')
                .then(resp => {
                    self.classes = resp.data.classes;
                })
                .catch(err => {
                    console.log(err);
                })
            } else if (isJustShown == true && collapseId == "crud-permissions") {
                this.resetData();

                var self = this;

                this.$http.get('/permissions-controllers')
                .then(resp => {
                    self.tree = resp.data.tree;
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
            this.nonControllers = [];
            this.active = [];
        },

        loadCrud(className) {
            this.$emit('loadContent', 'Crud', {selectedClassName: className});
            this.active = [];
            this.active[className] = 'active';
        },

        loadPermissions(vars) {
            this.$emit('loadContent', 'Permissions', {selectedMethod: vars});
        }
    },
    watch:{
        contentArgs: {
            handler: function(value) {
                this.getClassObjects(value.selectedClassName.split('\\').join('.'));
            }
        },
        currentPage: {
            handler: function(value) {
                this.getClassObjects(this.selectedClassName);
            }
        }
    },
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
