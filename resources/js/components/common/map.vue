<template>
    <div :class="className">
        <img :src="map.thumbnail_url" :alt="map.name" /><br />
        <div class="map-name attribute">{{map.name}}</div>
        <div class="map-size attribute" v-if="map.height && map.width">
            {{map.height / 51.2}}km x {{map.width / 51.2}}km
        </div>
        <div class="map-players attribute">
            <i class="fa fa-users fa-fw"></i> {{map.max_players}}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['value', 'classes', 'map'],
        data(){
            return {
                checked: this.value
            }
        },
        computed: {
            className(){
                let classes = this.classes;
                if (!Array.isArray(this.classes)) {
                    classes = [this.classes];
                }
                classes.push('map');

                return classes.join(' ')
            }
        },
    }
</script>
