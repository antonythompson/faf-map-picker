<template>
    <div>
        <h3>Maps</h3>
        <b-button v-b-modal.modal-new-map>Add new</b-button>

        <b-modal id="modal-new-map" title="BootstrapVue" @ok="save">
            <p>
                Each line is a map name.</p>
            <label for="map-name">
                <b-form-textarea id="map-name" type="text" v-model="names"></b-form-textarea>
            </label>
        </b-modal>

        <table class="table">
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <tr v-for="map in maps">
                <td>{{map.name}}</td>
                <td></td>
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
                id: null,
                adding: false,
                names: '',
                maps: [],
            }
        },
        async mounted() {
            let res = await apiController.index('maps');
            if (res.data) {
                this.maps = res.data;
            }
        },
        methods: {
            async save(){
                let arr = this.names.match(/[^\r\n]+/g);
                arr.forEach(async name => {
                    let map = await apiController.store('maps', {
                        name: name
                    });
                    if (map) {
                        this.maps.push(map);
                    }
                });
                this.adding = false;
                this.names = '';
            }
        }
    }
</script>
