<template>
    <div>
        <h3>Tournaments</h3>
        <b-button v-b-modal.modal-new-tournament>Add new</b-button>

        <b-modal id="modal-new-tournament" :title="modalTitle" @ok="save" v-model="show_modal">
            <label for="tournament-name">
                Name
                <b-form-input id="tournament-name" v-model="model.name" />
            </label>
            <br />
            <label for="tournament-ban-count">
                Bans per player
                <b-form-input type="number" id="tournament-ban-count" v-model="model.ban_count" />
            </label>
            <label for="tournament-ban-count">
                Maps left over
                <b-form-input type="number" id="tournament-map_count" v-model="model.map_count" />
            </label>
            <label for="map-search">
                Faf map search
            </label><br />
            <el-select
                id="map-search"
                v-model="map_search"
                filterable
                remote
                reserve-keyword
                placeholder="Search faf maps to add more"
                :remote-method="searchMaps"
                :loading="loading"
                @change="onMapAdd"
                >
                <el-option
                    v-for="(item, i) in search_maps"
                    :key="item.faf_id"
                    :label="mapLabel(item)"
                    :value="i">
                </el-option>
            </el-select>
            <div class="map-selection">
                <template v-for="map in maps">
                    <div :class="model.maps.indexOf(map.id) !== -1 ? 'active' : ''">
                        <label :for="'map-' + map.id">
                            <b-form-checkbox :id="'map-' + map.id" v-model="model.maps" :value="map.id" v-show="false"></b-form-checkbox>
                            <img :src="map.thumbnail_url" :alt="map.name" /><br />
                            <span>{{map.name}}</span>
                        </label>
                    </div>
                </template>
            </div>
        </b-modal>

        <table class="table">
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <tr v-for="tournament in tournaments">
                <td>{{tournament.name}}</td>
                <td>
                    <b-button @click="onEdit(tournament)" v-if="$loggedInUser.role_id === 2">Edit</b-button>
                    <router-link class="btn btn-primary" :to="{ name: 'matches', params: {tournament_id: tournament.id} }">Matches</router-link>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';
    export default {
        props: ['value'],
        data(){
            return {
                show_modal: false,
                loading: false,
                map_search: '',
                model: {
                    name: '',
                    ban_count: 5,
                    map_count: 5,
                    maps: [],
                },
                tournaments: [],
                search_maps: [],
                maps: [],
            }
        },
        async mounted() {
            this.getTournaments();
            let maps = await apiController.index('maps');
            if (maps.data) {
                this.maps = maps.data;
            }
        },
        computed: {
            modalTitle(){
                if (this.model && this.model.id) {
                    return 'Edit Tournament ' + this.model.name
                }
                return 'New Tournament'
            }
        },
        methods: {
            mapLabel(map){
                return `${map.displayName}`;
            },
            async searchMaps(term){
                let args = {term};
                this.loading = true;
                console.log('searchMaps', args);
                let maps = await apiController.get('maps.search', args);
                this.search_maps = maps.data;
                this.loading = false;
            },
            async onMapAdd(i){
                let map = this.search_maps[i];
                let exists = false;
                this.maps.forEach(m => {
                    if (m.faf_id === map.faf_id) {
                        exists = true;
                    }
                });
                if (!exists) {
                    let res = await apiController.post('maps.store', {
                        name: map.displayName,
                        width: map.width,
                        height: map.height,
                        max_players: map.maxPlayers,
                        version: map.version,
                        description: map.description,
                        faf_id: map.faf_id,
                        thumbnail_url: map.thumbnailUrlSmall,
                    });
                    this.maps.push(res.data);
                    this.model.maps.push(res.data.id);
                }
                this.map_search = '';

                console.log('onMapAdd', res);
            },
            async getTournaments(){
                let res = await apiController.index('tournaments');
                if (res.data) {
                    this.tournaments = res.data;
                }
            },
            resetModel(){
                this.model = {
                    name: '',
                    maps: [],
                };
            },
            async save(){
                if (this.model.id) {
                    await apiController.update('tournaments', this.model, this.model.id);
                } else {
                    await apiController.store('tournaments', this.model);
                }
                this.resetModel();
                this.getTournaments();
            },
            async onEdit(tournament){
                let model = JSON.parse(JSON.stringify(tournament));
                if (model.maps && model.maps.length) {
                    let ids = [];
                    model.maps.forEach(map => {
                         ids.push(map.id);
                    });
                    model.maps = ids;
                }
                this.model = model;
                this.show_modal = true;
            },
        }
    }
</script>
