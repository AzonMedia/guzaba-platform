<template>
    <div>
        <component v-if="hooks.hasOwnProperty(name) && hooks[name].hasOwnProperty('data')" :is="hookRender" :data="hooks[name]['data']" />
        <component v-else :is="hookRender" />
    </div>
</template>

<script>
// import hookMixin from '@/hooksMixins'
export default {
    // mixins: [hookMixin],
    name: 'Hook',
    props: ["name", "data"],
    replace: true,
    data() {
        return {
            hooks: {},
            hook: ''
        }
    },
    computed: {
        hookRender: function() {
            if (this.hook != '') {
                return () => import(`@/GuzabaPlatform/Platform/views/hooks/templates/${this.hooks[this.name]['name']}.vue`)
            }
        }
    },
    created() {
        let vm = this
        this.$http.interceptors.response.use((response) => {
            if (response.data.hasOwnProperty('hooks')) {
                Object.keys(response.data.hooks).forEach(el => {
                    if (typeof response.data.hooks[el] == 'object'
                        && response.data.hooks[el].hasOwnProperty('name')) {
                            if (response.data.hooks[el]['name'] != '') {
                                vm.hooks[el] = response.data.hooks[el]
                                vm.hook = response.data.hooks[el]
                            } else {
                                delete vm.hooks[el]
                            }
                    }
                })
            }
            return response
        })
    }
}
</script>