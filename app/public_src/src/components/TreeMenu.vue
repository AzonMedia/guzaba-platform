<template>
    <div class="tree-menu">
        <!-- TreeMenu.vue -->
        <div class="label-wrapper" @click="toggleChildren">
            <div :style="indent" :class="labelClasses" >
                <i class="fa" :class="iconClasses"></i>
                {{ label }}
            </div>
        </div>

        <tree-menu
            v-if="(showChildren && isObject(nodes))"
            v-for="(node, index) in nodes"
            v-bind:key="index"
            :nodes="node"
            :label="index"
            :depth="depth + 1"
            :contentToLoad="contentToLoad"
        >
        </tree-menu>
    </div>
</template>
<script>

export default { 
    props: [ 'label', 'nodes', 'depth', 'contentToLoad' ],
    data() {
        return {
            showChildren: false
        }
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
                this.contentToLoad(this.nodes);
            }
        },

        isObject(o) { 
            return typeof o == "object" 
        }
    }
}
</script>
