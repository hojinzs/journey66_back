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
                            @click="$router.push({name: 'Tags.Create'})"
                        >
                            등록
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
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
                    v-for="tag in tags"
                    :key="tag.id"
                >
                    <td>{{ tag.id }}</td>
                    <td>
                        <router-link
                            :to="{name: 'Tags.Show', params: {'tag_id': tag.id }}"
                        >
                            {{ tag.name }}
                        </router-link>
                    </td>
                    <td>{{ tag.type }}</td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
    </div>
</template>

<script>
    import LaravelResourceController from "../../../../mixin/LaravelResourceController.vue"

    export default {
        name: 'places',
        mixins: [ LaravelResourceController ],
        data(){
            return {
                tags: [],
                filter: {
                    name: null,
                }
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
        }
    }
</script>
