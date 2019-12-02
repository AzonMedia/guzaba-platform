<template>
    <div class="leftNav">
        <b-nav vertical class="w-25">
            <b-nav-item>
                <b-card no-body class="mb-1">
                    <b-card-header v-b-toggle.crud-operations class="p-1" role="tab">
                        <b-link href="#">Crud Operations</b-link>
                    </b-card-header>

                    <b-collapse id="crud-operations" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text v-for="classFullName in classes" @click="loadCrud(classFullName)" class="small" :class="active[classFullName]"> {{ classFullName }} </b-card-text>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </b-nav-item>

            <b-nav-item>
                <b-card no-body class="mb-1">
                    <b-card-header class="p-1" role="tab">
                        <b-link href="#" v-b-toggle.accordion-2>Example Accordion 2</b-link>
                    </b-card-header>

                    <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <b-card-text>Example Text</b-card-text>
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

export default {
    name: 'leftNav',
    components: {
        Hook
    },
    data() {
        return {
            classes: [],
            active: []
        }
    },
    mounted() {
        this.$root.$on('bv::collapse::state', (collapseId, isJustShown) => {
            if (collapseId == "crud-operations" && isJustShown == true) {
                this.classes = [];
                var self = this;

                this.$http.get('/crud-classes')
                .then(resp => {
                    self.classes = resp.data.classes;
                })
                .catch(err => {
                    console.log(err);
                })
            }
        })
    },
    methods: {
        loadCrud(className) {
            this.$emit('loadContent', 'Crud', {selectedClassName: className});
            this.active = [];
            this.active[className] = 'active';
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

p.card-text {
    margin-bottom: 5px;
    text-align: left;
}

.nav-link {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
}

.card-header a, .card-text, .nav-link {
    color: #2c3e50;
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
</style>
