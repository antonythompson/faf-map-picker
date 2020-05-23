<template>
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <router-link class="navbar-brand" :to="{ name: 'home' }">
                        <img src="/images/logo.png" alt="Map Picker" width="50"/>
                    </router-link>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav mr-auto">
                            <template v-if="$loggedInUser">
                                <router-link class="nav-item nav-link" :to="{ name: 'tournaments' }">Tournaments</router-link>
                                <router-link class="nav-item nav-link" :to="{ name: 'maps' }">Maps</router-link>
                            </template>
                        </div>
                        <div v-if="$loggedInUser">
                            <b-dropdown id="avatar-dropdown" v-if="$loggedInUser.discord.avatar" >
                                <template v-slot:button-content>
                                    <img :src="$loggedInUser.discord.avatar" alt="Discord Avatar" width="52"/>
                                </template>
                                <b-dropdown-item href="/logout">Logout</b-dropdown-item>
                            </b-dropdown>
                            <a v-else href="/logout">Logout</a>
                        </div>
                        <div v-else>
                            <b-button v-b-modal.login-modal>Login</b-button>
                            <b-modal id="login-modal" title="Login" hide-footer>
                                <login></login>
                            </b-modal>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-md-12">
                <router-view></router-view>
            </div>
            <whoareyou></whoareyou>

        </div>
    </div>
</template>

<script>
    import whoareyou from './pages/whoareyou';
    import login from './common/login';
    import Api from '../api/api_resource';
    export default {
        components: {
            'whoareyou': whoareyou,
            'login': login,
        },
        mounted() {
        }
    }
</script>
