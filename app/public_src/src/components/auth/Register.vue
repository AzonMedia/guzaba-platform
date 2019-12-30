<template>
    <div>
        <b-form inline @submit.stop.prevent="register">
            <label class="sr-only" for="inline-form-input-username">Email address</label>
            <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
              <b-input v-model="email"  id="inline-form-input-username" placeholder="Email address"></b-input>
            </b-input-group>

            <label class="sr-only" for="inline-form-input-username">Username</label>
            <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
              <b-input v-model="username" id="inline-form-input-username" placeholder="Username"></b-input>
            </b-input-group>

            <label class="sr-only" for="inline-form-input-username">Password</label>
            <b-input type="password" id="text-password" v-model="password" aria-describedby="password-help-block" placeholder="Password" required></b-input>

            <b-button type="submit" variant="primary">Register</b-button>
        </b-form>
    </div>
</template>
<script>
//import Hook from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
import Hook from '@GuzabaPlatform.Platform/components/hooks/Hooks.vue'
import { stringify } from 'qs'
export default {
  name: "Register",
  props: {
    msg: String
  },
    data() {
        return {
            username: null,
            password: null,
            email: null,
        }
    },
    mounted() {

    },
    methods: {
        register() {
            let email = this.email;
            let username = this.username;
            let password = this.password;

            this.$store.dispatch('register', { email, username, password })
            .then(() => this.$router.push('/'))
            .catch(err => console.log('Not authorized 2'))
            
            this.email = null;
            this.username = null;
            this.password = null;
        }
    }
};
</script>