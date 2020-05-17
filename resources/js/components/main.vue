<template>
    <div class="container">
        <div class="row">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <router-link class="navbar-brand" :to="{ name: 'home' }">
                    <img src="/images/logo.png" alt="Map Picker" width="50"/>
                </router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <router-link class="nav-item nav-link" :to="{ name: 'tournaments' }">Tournaments</router-link>
                        <router-link class="nav-item nav-link" :to="{ name: 'maps' }">Maps</router-link>
                    </div>
                </div>
            </nav>
            <div class="col-md-12">
                <router-view></router-view>
            </div>
            <whoareyou></whoareyou>

        </div>
    </div>
</template>

<script>
    import whoareyou from './pages/whoareyou';
    export default {
        components: {
            'whoareyou': whoareyou
        },
        mounted() {
            console.log('Main mounted.')
        }
    }
</script>
