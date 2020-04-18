<template>
    <div>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">type</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="place in places" :key="place.id">
                    <td>{{ place.id }}</td>
                    <td>{{ place.name }}</td>
                    <td>{{ place.type }}</td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
    </div>
</template>

<script>
    import LaravelResourceController from "../../../mixin/LaravelResourceController.vue"

    export default {
        name: 'places',
        mixins: [ LaravelResourceController ],
        data(){
            return {
                places: []
            }
        },
        methods: {
            updateAfterXhrSuccess(data){
                this.places = data
            }
        },
        created() {
            this.setXhr('GET','//'+this.$routeList('admin.api.places.index'))
            this.getData()
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
