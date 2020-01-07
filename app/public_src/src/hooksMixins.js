//NOT USED
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
                console.log('DEBUG1');
                return () => import(`@/views/hooks/templates/${this.hooks[this.name]['name']}.vue`)
            }
        }
    }
}
