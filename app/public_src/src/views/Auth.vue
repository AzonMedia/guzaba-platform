<template>
  <div class="container">
    <component :is="componentInstance"></component>
  </div>
</template>

<script>
// @ is an alias to /src

export default {
  name: "auth",
  computed: {
    componentInstance() {
      return () => import(`@/components/auth/${this.$route.name}.vue`)
    }
  }
};
</script>
