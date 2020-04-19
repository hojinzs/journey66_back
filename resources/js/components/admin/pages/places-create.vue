<template>
    <div>
        <place-form
            :mode="'POST'"
        />
    </div>
</template>

<script>
    import placeForm from './places-form'

    export default {
        name: 'CreatePlace',
        components: {
            placeForm
        },
        data(){
            return {
            }
        },
        computed: {
        },
        methods: {
        },
        created(){
        },
        mounted() {
        }
    }
</script>
