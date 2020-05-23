<template>
    <div>
        <template v-if="$loggedInUser">
            You are logged in.
        </template>
        <template v-else>
            <b-form-group label="Email">
                <b-form-input v-model="email" placeholder="Email"></b-form-input>
            </b-form-group>
            <b-form-group label="Password">
                <b-form-input v-model="password" placeholder="Password"></b-form-input>
            </b-form-group>
            <b-button @click="loginSubmit">Submit</b-button>
            <div class="login-or">OR</div>
            <a id="login-discord" href="/login/discord" class="btn btn-default">
                <img id="login-discord-image" class="mr-auto" src="/images/discord-logo-purple.svg" alt="Discord"/>
                Login with Discord
            </a>
        </template>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                email: '',
                password: ''
            }
        },
        mounted() {
            console.log('Login form.')
        },
        methods: {
            loginSubmit(){
                console.log('TODO:: handle submit')
            }
        }
    }
</script>
