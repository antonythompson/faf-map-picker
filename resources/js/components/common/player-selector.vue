<template>
        <el-select
            ref="elSelect"
            :id="id"
            v-model="selected_user_index"
            filterable
            remote
            reserve-keyword
            :placeholder="placeholder"
            :remote-method="searchUsers"
            :loading="loading"
            @change="onChange"
        >
            <el-option
                v-for="(item, i) in search_users"
                :key="item.faf_id"
                :label="item.login"
                :value="i">
            </el-option>
        </el-select>
</template>

<script>
    import apiController from '../../api/api_resource';

    export default {
        props: {
            value: Object,
            id: String,
            create: {
                type: Boolean,
                default: true
            },
            placeholder: {
                type: String,
                default: 'Search FAF users'
            },
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
            if (this.value && this.value.id) {
                this.search_users = [this.value];
                this.selected_user_index = 0;
            }
            console.log('mounted user select');
        },
        methods: {
            async onChange(){
                let user = this.search_users[this.selected_user_index];
                if (this.create) {
                    let res = await apiController.post('players.createFaf', user);
                    if (res.data) {
                        user = res.data;
                    }
                }
                this.user = user;
                this.$emit('input', user);
            },
            async searchUsers(term){
                let args = {term};
                this.loading = true;
                console.log('searchUsers', args);
                let res = await apiController.get('players.search', args);
                this.search_users = res.data;
                this.loading = false;
            },
            focus(){
                this.$refs.elSelect.focus();
            }
        }
    }
</script>
