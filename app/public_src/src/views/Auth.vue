<template>
  <div class="container">
    <component :is="componentInstance"></component>
  </div>
</template>

<script>
// @ is an alias to /src
//this component is not used

export default {
  name: "auth",
  computed: {
    componentInstance() {
      //return () => import(`@/GuzabaPlatform/Platform/components/auth/${this.$route.name}.vue`)
      //works if the route is just user or login
      return () => import(`@GuzabaPlatform.Platform/components/auth/${this.$route.name}.vue`)
    }
  }
};
</script>
