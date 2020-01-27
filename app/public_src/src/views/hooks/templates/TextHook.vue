<template>
    <div>{{ data.text }}</div>
</template>
<script>
    //import Hooks from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
    import Hooks from '@GuzabaPlatform.Platform/components/hooks/Hooks.vue'
    export default {
        name: "TextHook",
        extends: Hooks,
    }
</script>