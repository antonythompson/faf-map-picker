<template>
    <div>
        <h3>{{match.tournament.name}}</h3>
        <h4 v-if="match.player_one && match.player_two">{{match.player_one.login}} vs {{match.player_two.login}}</h4>

        <h4>Maps</h4>
        <b-button @click="startPicking">Start Picking</b-button>
        <div class="map-selection">
            <template v-for="map in match.tournament.maps">
                <div :class="mapClass(map.id)" @click="onClick(map)">
                    <label :for="'map-' + map.id">
                        <img :src="map.thumbnail_url" :alt="map.name" /><br />
                        <span>{{map.name}}</span>
                    </label>
                </div>
            </template>
        </div>
        {{connected_users}}
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';

    export default {
        props: ['tournament_id', 'match_id'],
        data(){
            return {
                match: {
                    id: null,
                    player_one_id: null,
                    player_two_id: null,
                    tournament: {
                        name: '',
                        ban_count: null,
                        map_count: null,
                        maps: [],
                    },
                },
                my_bans: [],
                their_bans: [],
                connected_users: [],
            }
        },
        mounted() {
            console.log('mounted picker');
            this.setup();
        },
        methods: {
            async setup(){
                await this.getMatch(this.match_id);
                if (this.match.id) {
                    this.listen();
                }
            },
            async getMatch(id){
                if (this.match_id) {
                    console.log('getMatch', id);
                    let res = await apiController.show('matches', {load: 'tournament.maps,playerOne,playerTwo,bannedMaps,pickedMaps'}, {match: id});
                    this.match = res.data;
                    this.checkMatch();
                }
            },
            startPicking(){

            },
            checkMatch(){
                //check the players

                //get the bans
                if (this.match.banned_maps) {
                    this.match.banned_maps.forEach(map => {
                        if (map.pivot.banned_by) {

                        }
                    });
                }
            },
            listen(){
                let channel = this.$pusher.subscribe(`presence-match-${this.match_id}`);
                channel.bind('map-banned', async ({ data }) => {
                    await this.getMatch(this.match_id);
                    console.log('map banned', data);
                });
                channel.bind('pusher:subscription_succeeded', members => {
                    this.connected_users = members;
                });
                this.connected_users = channel.members;
            },

            async onClick(map){
                // let args = {
                //     player_id: this.$currentPlayer.id,
                //     map_id: map.id
                // };
                // let res = await apiController.post('matches.banMap', args, this.match.id);
                // if (res.data) {
                //     res.data
                //     this.checkMatch();
                // }
            },
            async createNew(){
                let match = await apiController.store('matches', {tournament_id: this.tournament_id});

                console.log('new', match);
            },
            mapClass(id){
                let classes = [];
                if (this.my_bans.indexOf(id)) {
                    classes.push('my-ban');
                }
                if (this.their_bans.indexOf(id)) {
                    classes.push('my-ban');
                }
                return classes;
            },
        }
    }
</script>
