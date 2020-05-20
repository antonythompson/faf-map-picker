<template>
    <div v-if="messages" >
        <template v-for="msg in messages">
            <b-alert v-bind:variant="msg.type" show>{{msg.message}}</b-alert>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            messages: {
                type: Array,
                default: [],
            }
        }
    }
</script>
