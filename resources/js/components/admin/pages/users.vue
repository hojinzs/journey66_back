<template>
    <div>
        <v-form @submit.prevent="setFilter">
            <v-container>
                <v-row>
                    <v-col
                        cols="12"
                        md="4"
                    >
                        <v-text-field
                            v-model="filter.name"
                            :counter="10"
                            label="Name"
                        />
                    </v-col>
                    <v-col
                        cols="12"
                        md="4"
                    >
                        <v-text-field
                            v-model="filter.email"
                            :counter="20"
                            label="e-mail"
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                        cols="12"
                        md="4"
                    >
                        <v-btn type="submit">
                            Filter
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-divider />
        <v-simple-table>
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">#</th>
                    <th class="text-left">Name</th>
                    <th class="text-left">e-mail</th>
                    <th class="text-left">created_at</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }} | <span v-if="user.email_verified_at">V</span></td>
                    <td>{{ user.created_at }}</td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
        <v-pagination
            v-model="page"
            :length="meta.last_page"
        ></v-pagination>
    </div>
</template>

<script>
    import LaravelResourceController from '../../../mixin/LaravelResourceController.vue'

    export default {
        name: 'users',
        mixins: [LaravelResourceController],
        data(){
            return {
                users: this.data,
                filter: {
                    name: null,
                    email: null,
                },
            }
        },
        created() {
            this.setXhr('GET','//'+this.$routeList('admin.api.users.index'))
            this.getData()
                .then(res => console.log("success =>", res))
                .catch( error => console.error(error))
        },
        mounted() {
        },
    }
</script>
