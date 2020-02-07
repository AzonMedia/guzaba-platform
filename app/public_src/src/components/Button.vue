<template>
    <div class="button" v-bind:class="{ active: ButtonData.is_active }">{{ButtonData.label}}</div>
</template>

<script>
    export default {
        name: "ButtonC",

        props: {
            ButtonData : Object
        },
    }
</script>

<style scoped>
.button {
    border: 1px solid green;
    float: left;
    margin: 3px;
    padding: 3px;
}
</style>