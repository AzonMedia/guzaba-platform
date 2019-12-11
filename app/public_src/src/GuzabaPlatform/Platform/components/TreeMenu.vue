<template>
    <div class="tree-menu">
        <div class="label-wrapper" @click="toggleChildren">
            <div :style="indent" :class="labelClasses" >
                <i class="fa" :class="iconClasses"></i>
                {{ label }}
            </div>
        </div>
        <tree-menu
            @loadPermissions="loadPermissions"
            v-if="(showChildren && isObject(nodes))"
            v-for="(node, index) in nodes" 
            :nodes="node"
            :label="index"
            :depth="depth + 1"
        >
        </tree-menu>
    </div>
</template>
<script>

export default { 
    props: [ 'label', 'nodes', 'depth' ],
    data() {
        return { showChildren: false }
    },
    name: 'tree-menu',
    computed: {
        iconClasses() {
            return {
                'fa-plus-square-o': !this.showChildren,
                'fa-minus-square-o': this.showChildren
            }
        },
        labelClasses() {
            return { 'has-children': this.isObject(this.nodes) }
        },
        indent() {
            return { 
                transform: `translate(${this.depth * 24}px)` 
            }
        }
    },

    methods: {
        toggleChildren() {
            if (this.isObject(this.nodes)) {
                this.showChildren = !this.showChildren;
            } else {
                this.loadPermissions(this.nodes);
            }
        },

        isObject(o) { 
            return typeof o == "object" 
        },

        loadPermissions(method) {
            this.$emit('loadPermissions', method);
            //this.active = [];
            //this.active[className] = 'active';
        },

    }
}
</script>
