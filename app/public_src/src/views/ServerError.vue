<template>
    <div class="view_page">
        500
        <h2>{{ObjectData.page_name}}</h2>
        <span v-html="ObjectData.page_content"></span>
    </div>
</template>

<script>
    export default {
        name: "ServerError",
        props: {
            ObjectData : Object
        },
    }
</script>

<style scoped>

</style>