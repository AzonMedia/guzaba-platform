<template>
    <div>
        <!--
        <component v-if="hooks.hasOwnProperty(name) && hooks[name].hasOwnProperty('data')" :is="hookRender" :data="hooks[name]['data']" />
        <component v-else :is="hookRender" />
        -->

        <!--
        <component :is="hookRender" />
        -->

        <!--
        <div v-for="(hook,index) in hooks[name]">
            A
            <component :is="hookRender(hook, index)"/>
        </div>
        -->

        <component v-if="hooks.hasOwnProperty(name)" :is="hook_render_0" :data="hooks[name][0]['data']" />
        <component v-if="hooks.hasOwnProperty(name)" :is="hook_render_1" :data="hooks[name][1]['data']" />
        <component v-if="hooks.hasOwnProperty(name)" :is="hook_render_2" :data="hooks[name][2]['data']" />
        <component v-if="hooks.hasOwnProperty(name)" :is="hook_render_3" :data="hooks[name][3]['data']" />
        <component v-if="hooks.hasOwnProperty(name)" :is="hook_render_4" :data="hooks[name][4]['data']" />
    </div>
</template>

<script>

//const aliases = require('@/../components_config/webpack.components.config.js').aliases
const aliases = require('@/../components_config/webpack.components.runtime.json')

export default {
    // mixins: [hookMixin],
    name: 'Hook',
    //props: ["name", "data"],
    props: ["data"],
    replace: true,
    data() {
        return {
            name: '',
            counter: 0,
            hooks: {},
        }
    },
    methods: {
        hook_render: function(index) {
            if (this.name && this.hooks[this.name] && this.hooks[this.name][index] && this.hooks[this.name][index]['name']) {
                let hook_name = this.hooks[this.name][index]['name'];

                for (const alias in aliases) {
                    if (alias !='@' && hook_name.indexOf(alias) != -1) {
                        hook_name = hook_name.replace(alias, aliases[alias]);
                        break;
                    }
                }
                //console.log(hook_name);
                //due to the static analisys Webpack does the import must have a path or alias hardcoded
                //replace the VENDOR path in the
                hook_name = hook_name.replace(aliases['@VENDOR'], '');
                hook_name = hook_name.replace('Hook.vue', '');
                //this causes the whole vendor directory to be searched for...
                //console.log(hook_name);
                //return () => import(`@VENDOR${hook_name}Hook.vue`) // not working... TODO - fix this
                return () => import(`@/../../../vendor${hook_name}Hook.vue`)
                //return () => import(`/home/local/PROJECTS/guzaba-platform-skeleton/vendor${hook_name}Hook.vue`)
                //return () => import('/home/local/PROJECTS/guzaba-platform-skeleton/vendor/guzaba-platform/guzaba-platform/app/public_src/src/views/hooks/templates/TextHook.vue')
                //let z = '/guzaba-platform/guzaba-platform/app/public_src/src/views/hooks/templates/Text';
                //return () => import(`/home/local/PROJECTS/guzaba-platform-skeleton/vendor${z}Hook.vue`)
            }
        },
    },
    computed: {
        hook_render_0: function () {
            this.counter;//just to make this computed property dependent on something
            return this.hook_render(0);
        },
        hook_render_1: function () {
            this.counter;
            return this.hook_render(1);
        },
        hook_render_2: function () {
            this.counter;
            return this.hook_render(2);
        },
        hook_render_3: function () {
            this.counter;
            return this.hook_render(3);
        },
        hook_render_4: function () {
            this.counter;
            return this.hook_render(4);
        },
    },
    created() {
        let vm = this
        this.$http.interceptors.response.use((response) => {
            if (response.data.hasOwnProperty('hooks')) {

                Object.keys(response.data.hooks).forEach(el => {

                    vm.hooks[el] = [];

                    for (const hook_el in response.data.hooks[el]) {
                        if (typeof response.data.hooks[el][hook_el] == 'object' && response.data.hooks[el][hook_el].hasOwnProperty('name')) {

                            if (response.data.hooks[el][hook_el]['name'] != '') {
                                //vm.hooks[el] = response.data.hooks[el][hook_el]
                                //vm.hook = response.data.hooks[el]
                                vm.name = el;
                                vm.hooks[el].push(response.data.hooks[el][hook_el])
                                //console.log(response.data.hooks[el][hook_el]);
                            } else {
                                delete vm.hooks[el][hook_el]
                            }
                        }
                    }

                    for (let aa=0 ; aa < 5; aa++) {
                        if (typeof vm.hooks[el][aa] === "undefined") {
                            vm.hooks[el][aa] = {};
                            vm.hooks[el][aa]['data'] = {};
                        }
                    }

                    this.counter++;//to trigger recalculation of the computed properties
                    //vm.$forceUpdate();

                })
            }
            return response
        })
    }
}
</script>