<template>
    <div>
        <v-progress-linear
            :active="xhrLoading"
            :indeterminate="xhrLoading"
            absolute
            top
            color="deep-purple accent-4"
        ></v-progress-linear>
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
                </v-row>
                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-btn type="submit">
                            Filter
                        </v-btn>
                    </v-col>
                    <v-col
                        :class="'d-flex justify-end'"
                        cols="12"
                        md="6"
                    >
                        <v-btn
                            class="white--text"
                            color="deep-purple accent-4"
                            @click="$router.push({name: 'Places.create'})"
                        >
                            등록
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
                    <th class="text-left">type</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="place in places"
                    :key="place.id"
                >
                    <td>{{ place.id }}</td>
                    <td>
                        <router-link
                            :to="{name: 'Places.show', params: { place_id: place.id }}"
                        >
                            {{ place.name }}
                        </router-link>
                    </td>
                    <td>{{ place.type }}</td>
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
    import LaravelResourceController from "../../../../mixin/LaravelResourceController.vue"

    export default {
        name: 'places',
        mixins: [ LaravelResourceController ],
        data(){
            return {
                places: [],
                filter: {
                    name: null,
                }
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
