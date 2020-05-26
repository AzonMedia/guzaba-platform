<template>
    <div class="container">
        <b-form inline @submit.stop.prevent="register">

            <div>
                <!-- <b-alert :show="Alert.show" dismissible class="messages">{{Alert.message}}</b-alert> -->
                <!-- @see https://stackoverflow.com/questions/59265828/once-dismissed-the-boostrap-vue-alert-its-not-working-again -->
                <b-alert v-model="Alert.show" dismissible class="messages">{{Alert.message}}</b-alert>
            </div>

            <div>
                <label class="sr-only" for="inline-form-input-user-email">Email address</label>
                <b-input-group prepend="@" class="mb-2 mr-sm-2 mb-sm-0">
                    <b-input v-model="User.user_email" id="inline-form-input-user-email" placeholder="Email address"></b-input>
                </b-input-group>
            </div>

            <div>
                <label class="sr-only" for="inline-form-input-user-name">Username</label>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-input v-model="User.user_name" id="inline-form-input-user-name" placeholder="Username"></b-input>
                </b-input-group>
            </div>

            <div>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <p>The password must contain at least one digit, one small letter, one CAPITAL letter and one symbol (like *#@%%!$+_^<>.,).</p>
                </b-input-group>
            </div>
            <div>
                <label class="sr-only" for="inline-form-input-user-password">Password</label>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-input type="password" id="inline-form-input-user-password" v-model="User.user_password" aria-describedby="password-help-block" placeholder="Password" required></b-input>
                </b-input-group>
            </div>

            <div>
                <label class="sr-only" for="inline-form-input-user-password-confirmation">Password confirmation</label>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-input type="password" id="inline-form-input-user-password-confirmation" v-model="User.user_password_confirmation" aria-describedby="password-help-block" placeholder="Password confirmation" required></b-input>
                </b-input-group>
            </div>

            <div>
                <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
                    <b-button type="submit" variant="primary">Register</b-button>
                </b-input-group>
            </div>

            <div>
                <Hook :name="'_after_main'"/>
            </div>
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
                Alert: {
                    show: false,
                    message: '',
                }
            }
        },
        mounted() {

        },
        methods: {
            register() {
                let url = '/user/register';
                //let self = this;
                //let SendValues = {};
                //SendValues.new_directory_name = this.new_directory_name;
                this.$http.post(url, this.User).
                then(() => {
                    //self.$parent.get_dir_files(self.$parent.CurrentDirPath.name);
                    this.$router.push('/user/login');
                }).catch(err => {
                    //console.log(err);
                    //self.Alert.message = err.response.data.message.split("\n").join("<br />");
                    //this.Alert.message = err.response.data.message;
                    //this.Alert.show = true;
                    this.show_alert(err.response.data.message)
                }).finally(function(){

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
            },
            show_alert(message) {
                this.Alert.message = message;
                this.Alert.show = true;
            }
        }
    };
</script>


<style scoped>
.messages {
    white-space: pre-line;
    text-align: left;
}
</style>