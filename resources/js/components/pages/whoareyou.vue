<template>
    <div>
        <b-modal id="modal-user-selection" ref="modal" title="Who are you?" @ok="save" v-model="show_modal">
            <div class="user-selection">
                <label for="user-search">
                    Before we get started, please find your FAF user.
                </label><br />
                <player-select id="user-search" v-model="player"></player-select>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';
    import playerSelect from '../common/player-selector';
    import bootstrapValidation from '../mixins/bootstrap-validation';
    export default {
        components: {
            'player-select': playerSelect,
        },
        mixins: [bootstrapValidation],
        data(){
            return {
                show_modal: false,
                loading: false,
                selected_user_index: null,
                search_users: [],
                player: null
            }
        },
        mounted() {
            if (this.$loggedInUser && !this.$loggedInUser.player) {
                this.show_modal = true;
            }
            console.log('mounted whoareyou');
        },
        methods: {
            async save(e){
                e.preventDefault();
                if (this.player && this.player.id && this.$loggedInUser) {
                    let args = {
                        user_id: this.$loggedInUser.id
                    };
                    let res = await apiController.update('players', args, this.player.id);
                    if (res.data) {
                        this.show_modal = false;
                    }
                }
                return false
            }
        }
    }
</script>
