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
                <tr v-for="tag in tags" :key="tag.id">
                    <td>{{ tag.id }}</td>
                    <td>{{ tag.name }}</td>
                    <td>{{ tag.type }}</td>
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
                tags: []
            }
        },
        methods: {
            updateAfterXhrSuccess(data){
                this.tags = data
            }
        },
        created() {
            this.setXhr('GET','//'+this.$routeList('admin.api.tags.index'))
            this.getData()
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
