<template>
    <div>
        <tag-form
            :mode="'POST'"
        />
    </div>
</template>

<script>
    import tagForm from './form'

    export default {
        name: 'CreateTag',
        components: {
            tagForm
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
