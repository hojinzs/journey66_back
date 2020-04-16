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
    export default {
        name: 'places',
        data(){
            return {
                places: []
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
