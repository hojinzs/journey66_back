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
    export default {
        name: 'places',
        data(){
            return {
                tags: []
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
