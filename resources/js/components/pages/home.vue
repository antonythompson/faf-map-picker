<template>
    <div>
        <h3>Please login</h3>
        <div class="row">
            <div class="col-md-6">
                <login></login>
            </div>
        </div>
    </div>
</template>

<script>

    import login from '../common/login';

    export default {
        components: {
            login
        },
        mounted() {
            console.log('Home mounted.')
        }
    }
</script>
