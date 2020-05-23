<template>
    <div>
        <h3>Tournament: {{match.tournament.name}}</h3>
        <h4 v-if="match.player_one && match.player_two">{{match.player_one.login}} vs {{match.player_two.login}}</h4>
        <h2>Status: <b-badge :variant="statusVariant">{{status}}</b-badge></h2>
        <h4>Maps</h4>
        <b-button @click="startPicking" v-if="ready && !started">Start Picking</b-button>
        <h3 v-if="done">Play the maps in this order</h3>
        <div class="map-selection" v-if="done">
            <template v-for="map in match.picked_maps">
                <div :class="mapClass(map.id)" @click="onClick(map)">
                    <label :for="'map-' + map.id">
                        <img :src="map.thumbnail_url" :alt="map.name" /><br />
                        <span>{{map.name}}</span>
                    </label>
                </div>
            </template>
        </div>
        <h3 v-if="done">Banned maps</h3>
        <div class="map-selection">
            <template v-for="map in match.banned_maps">
                <div :class="mapClass(map.id)" @click="onClick(map)">
                    <label :for="'map-' + map.id">
                        <img :src="map.thumbnail_url" :alt="map.name" /><br />
                        <span>{{map.name}}</span>
                    </label>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';
    let channel;
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
                ready: false,
                started: false,
                my_turn: false,
                done: false,
                message_override: false,
            }
        },
        computed: {
            status(){
                if (this.message_override) {
                    return this.message_override;
                }
                if (this.done) {
                    return 'Done. Checks picks below';
                }
                if (!this.ready) {
                    return 'Waiting for other player';
                }else {
                    if (this.started) {
                        return this.my_turn ? 'Choose a map to ban' : 'Waiting for other player to pick';
                    }
                    return 'Click start to begin';
                }
            },
            statusVariant(){
                if (this.message_override) {
                    return 'danger';
                }
                if (this.done) {
                    return 'success';
                }
                if (!this.ready) {
                    return 'info';
                } else {
                    if (this.started) {
                        return this.my_turn ? 'success' : 'danger';
                    }
                    return 'info';
                }
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
                channel.whisper('starting', {
                    name: this.$loggedInUser.player.login
                });
                this.started = true;
                this.my_turn = true;
            },
            checkMatch(){
                //check the players
                let theirs = [];
                let mine = [];
                //get the bans
                if (this.match.banned_maps && this.match.banned_maps.length) {
                    this.match.banned_maps.forEach(map => {
                        if (map.pivot.banned_by === this.$loggedInUser.player.id) {
                            mine.push(map.id);
                        } else {
                            theirs.push(map.id);
                        }
                    });
                    this.started = true;
                    let last = this.match.banned_maps[this.match.banned_maps.length - 1];
                    if (last.pivot.banned_by === this.$loggedInUser.player.id) {
                        this.my_turn = false;
                    } else {
                        this.my_turn = true;
                    }
                }
                this.my_bans = mine;
                this.their_bans = theirs;
                let count = this.match.tournament.ban_count;
                if (mine.length === count && theirs.length === count) {
                    this.done = true;
                }
            },
            listen(){

                initEcho();
                channel = Echo.join(`match.${this.match_id}`)
                    .notification((notification) => {
                        console.log(notification);
                    })
                    .here((users) => {
                        if (users.length === 2) {
                            this.ready = true;
                        }
                    })
                    .joining((user) => {
                        if (this.$loggedInUser.player.id && user.player_id !== this.$loggedInUser.player.id) {
                            this.ready = true;
                        }
                    })
                    .listenForWhisper('starting', e => {
                        this.my_turn = false;
                        this.started = true;
                    })
                    .listenForWhisper('picked', e => {
                        this.getMatch(this.match_id);
                        this.my_turn = true;
                    })
                    .leaving((user) => {
                        this.ready = false;
                    })

                // let channel = this.$pusher.subscribe(`presence-match-${this.match_id}`);
                // channel.bind('App\\Events\\MatchImHere', async ({ data }) => {
                //     await this.getMatch(this.match_id);
                //     console.log('map banned', data);
                // });
                // channel.bind('pusher:subscription_succeeded', members => {
                //     this.connected_users = members;
                // });
                // this.connected_users = channel.members;
            },

            async onClick(map){
                if (this.my_turn && !this.done) {
                    if (this.my_bans.indexOf(map.id) !== -1) {
                        this.message_override = 'You already picked that map!';
                    } else if (this.their_bans.indexOf(map.id) !== -1) {
                        this.message_override = 'They already picked that map!';
                    } else {
                        this.message_override = false;
                        this.my_bans.push(map.id);
                        this.my_turn = false;
                        let args = {
                            map_id: map.id,
                            player_id: this.$loggedInUser.player.id
                        };
                        console.log('ban', args);
                        let res = await apiController.post('matches.banMap', args, this.match.id);
                        if (res.data) {
                            this.getMatch(this.match_id);
                            channel.whisper('picked', {
                                map
                            });
                        }
                    }
                }
                // let res = await apiController.get('matches.imHere', {}, [this.match.id, this.$loggedInUser.player.id]);
                // console.log('onClick', res);
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
                if (!this.done) {
                    if (this.my_bans.indexOf(id) !== -1) {
                        classes.push('my-ban');
                    }
                    if (this.their_bans.indexOf(id) !== -1) {
                        classes.push('their-ban');
                    }
                }
                return classes;
            },
        }
    }
</script>
