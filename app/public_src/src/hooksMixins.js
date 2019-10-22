export default {
    name: 'Hook',
    props: ["name", "data"],
    data() {
        return {
            hooks: {},
            hook: ''
        }
    },
    computed: {
        hookRender: function() {
            if (this.hook != '') {
                return () => import(`@/views/hooks/templates/${this.hooks[this.name]['name']}.vue`)
            }
        }
    }
}
