<template>
    <div>
        <b-form inline @submit.stop.prevent="register">

            <p id="message"></p>

            <p>
                <label class="sr-only" for="inline-form-input-user-email">Email address</label>
                <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                  <b-input v-model="User.user_email" id="inline-form-input-user-email" placeholder="Email address"></b-input>
                </b-input-group>
            </p>

            <p>
                <label class="sr-only" for="inline-form-input-user-name">Username</label>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                  <b-input v-model="User.user_name" id="inline-form-input-user-name" placeholder="Username"></b-input>
                </b-input-group>
            </p>

            <p>
                <label class="sr-only" for="inline-form-input-user-password">Password</label>
                <b-input type="password" id="inline-form-input-user-password" v-model="User.user_password" aria-describedby="password-help-block" placeholder="Password" required></b-input>
            </p>

            <p>
                <label class="sr-only" for="inline-form-input-user-password-confirmation">Password confirmation</label>
                <b-input type="password" id="inline-form-input-user-password-confirmation" v-model="User.user_password_confirmation" aria-describedby="password-help-block" placeholder="Password confirmation" required></b-input>
            </p>

            <p>
                <b-button type="submit" variant="primary">Register</b-button>
            </p>

            <p>
                <Hook :name="'_after_main'"/>
            </p>
        </b-form>
    </div>
</template>
<script>

//import Hook from '@/GuzabaPlatform/Platform/components/hooks/Hooks.vue'
import Hook from '@GuzabaPlatform.Platform/components/hooks/Hooks.vue'
//import { stringify } from 'qs'

export default {
    name: "Register",
    components: {
        Hook
    },
    props: {
        msg: String
    },
    data() {
        return {
            User: {
                user_email: null,
                user_name: null,
                user_password: null,
                user_password_confirmation: null,
            },
            message: '',
        }
    },
    mounted() {

    },
    methods: {
        register() {
            let url = '/user/register';
            let self = this;
            //let SendValues = {};
            //SendValues.new_directory_name = this.new_directory_name;
            this.$http.post(url, this.User).
            then(function() {
                //self.$parent.get_dir_files(self.$parent.CurrentDirPath.name);
                self.$router.push('/user/login');
            }).catch(function(err) {
                self.message = err.response.data.message
                //self.$parent.get_dir_files(self.$parent.CurrentDirPath.name);//refresh just in case
                //self.$parent.show_toast(err.response.data.message);
            }).finally(function(){
                //self.$parent.get_dir_files(self.ModalData.CurrentDirPath.name);//refresh just in case
                //
            });
            // let email = this.email;
            // let username = this.username;
            // let password = this.password;
            //
            // this.$store.dispatch('register', { email, username, password })
            // .then(() => this.$router.push('/'))
            // .catch(err => console.log('Not authorized 2'))
            //
            // this.email = null;
            // this.username = null;
            // this.password = null;
        }
    }
};
</script>