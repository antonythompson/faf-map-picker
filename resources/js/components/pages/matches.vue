<template>
    <div>
        <h3>Matches for <template v-if="tournament.name">{{tournament.name}}</template></h3>
        <b-button v-b-modal.modal-new-match @click="focus_user = true">Add new</b-button>

        <b-modal id="modal-new-match" :title="modalTitle" @ok="save" v-model="show_modal" @hidden="onHide" @shown="onShown">
            <player-select ref="player_one" v-model="model.player_one"></player-select>
            <player-select v-model="model.player_two"></player-select>
            <template v-slot:modal-footer>
                <template v-if="model.id">
                    <b-button @click="onSave">Save & close</b-button>
                </template>
                <template v-else>
                    <b-button @click="onSaveClose">Save & close</b-button>
                    <b-button @click="onSave">Save & add another</b-button>
                </template>
            </template>
        </b-modal>

        <table class="table striped">
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <tr v-for="match in tournament.matches">
                <td>
                    <span v-if="match.player_one">{{match.player_one.login}}</span>
                    vs
                    <span v-if="match.player_two">{{match.player_two.login}}</span>
                <td>
                    <b-button v-if="match.status_id === 2 && $loggedInUser.role_id === 2" @click="onEdit(match)">Edit</b-button>
                    <b-button @click="$router.push({name: 'match', params: {match_id: match.id}})" :variant="showPick(match) ? 'success' : ''">
                        <template v-if="showPick(match)">
                            Pick
                        </template>
                        <template v-else>View</template>
                    </b-button>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';
    import playerSelect from '../common/player-selector';
    export default {
        components: {
            'player-select': playerSelect,
        },
        props: ['tournament_id'],
        data(){
            return {
                show_modal: false,
                loading: false,
                focus_user: false,
                map_search: '',
                model: {
                    player_one: {},
                    player_two: {},
                    tournament_id: this.tournament_id,
                },
                tournament: {},
                search_maps: [],
                maps: [], 
            }
        },
        async mounted() {
            this.getTournament();
        },
        computed: {
            modalTitle(){
                if (this.model && this.model.id) {
                    return 'Edit Match'
                }
                return 'Add match';
            }
        },
        methods: {
            showPick(match){
                if (match.player_one && match.player_two
                    && (this.$loggedInUser.player.id ===   match.player_two_id
                        ||
                        this.$loggedInUser.player.id ===   match.player_one_id)){
                    return true;
                }
                return false;
            },
            async onSaveClose(){
                this.onSave();
                this.show_modal = false;
            },
            onHide(){
                this.resetModel();
            },
            onShown(){
                if (!this.model.id) {
                    this.$refs.player_one.focus();
                }
            },
            async onSave(){
                let res;
                let args = {
                    player_one_id: this.model.player_one.id,
                    player_two_id: this.model.player_two.id,
                    tournament_id: this.model.tournament_id,
                };
                if (this.model.id) {

                    res = await apiController.update('matches', args, this.model.id);
                } else {
                    res = await apiController.store('matches', args);
                }

                if (res.data) {
                    this.tournament.matches.push(res.data);
                    this.player_one = {};
                    this.player_two = {};
                    this.$refs.player_one.focus();
                }
            },
            async getTournament(){
                let res = await apiController.show('tournaments', {load: 'matches.playerOne,matches.playerTwo'}, {tournament: this.tournament_id});
                if (res.data) {
                    this.tournament = res.data;
                }
            },
            resetModel(){
                this.model = {
                    player_one: {},
                    player_two: {},
                    tournament_id: this.tournament_id,
                };
            },
            async save(){
                let args = {
                    player_one_id: this.model.player_one.id,
                    player_two_id: this.model.player_two.id,
                    tournament_id: this.tournament_id,
                };
                if (this.model.id) {
                    args.id = this.model.id;
                    await apiController.update('tournaments', args, this.model.id);
                } else {
                    await apiController.store('tournaments', args);
                }
                this.resetModel();
                this.getTournaments();
            },
            async onEdit(match){
                let model = JSON.parse(JSON.stringify(match));
                this.model = model;
                this.focus_user = false;
                this.show_modal = true;
            },
        }
    }
</script>
