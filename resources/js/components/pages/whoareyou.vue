<template>
    <div>
        <b-modal id="modal-user-selection" ref="modal" title="Who are you?" @ok="save"
                 @hide="save" v-model="show_modal">
            <div class="user-selection">
                <label for="user-search">
                    Before we get started, please find your FAF user.
                </label><br />
                <player-select id="user-search" v-model="user"></player-select>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import apiController from '../../api/api_resource';
    import playerSelect from '../common/player-selector';

    export default {
        components: {
            'player-select': playerSelect,
        },
        data(){
            return {
                show_modal: false,
                loading: false,
                selected_user_index: null,
                search_users: [],
                user: null
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
                if (this.user && this.user.id) {
                    return true;
                }
                if (this.$currentPlayer) {
                    return true;
                }
                return false
            }
        }
    }
</script>
