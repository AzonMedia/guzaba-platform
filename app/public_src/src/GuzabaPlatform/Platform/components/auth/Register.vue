<template>
  <div>
    <b-form inline>
      <label class="sr-only" for="inline-form-input-name">Name</label>
      <b-input id="inline-form-input-name" class="mb-2 mr-sm-2 mb-sm-0" placeholder="Jane Doe"></b-input>

      <label class="sr-only" for="inline-form-input-username">Username</label>
      <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
        <b-input id="inline-form-input-username" placeholder="Username"></b-input>
      </b-input-group>

      <b-button variant="primary">Register</b-button>
    </b-form>
  </div>
</template>
<script>
export default {
  name: "Register",
  props: {
    msg: String
  }
};
</script>