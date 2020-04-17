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
    import axios from 'axios'
    import queryString from 'query-string'
    import cleanDeep from 'clean-deep'

    export default {
        name: 'users',
        data(){
            return {
                users: [],
                links: {},
                meta: {
                    current_page: 1,
                    from: 1,
                    last_page: 1,
                    path: '//' + this.$routeList('admin.api.users.index'),
                    per_page: 15,
                    to: 1,
                    total: 1
                },
                filter: {
                    name: null,
                    email: null,
                },
                page: 1
            }
        },
        created() {
            this.getUsersData()
                .then(res => console.log("success =>", res))
        },
        mounted() {

        },
        methods: {
            async getUsersData(query = {}){
                let new_query = { ...query }

                if(typeof new_query.per_page == 'undefined'){
                    new_query.per_page = this.meta.per_page
                }

                let apiUrl = queryString.stringifyUrl({
                    url: this.meta.path,
                    query: new_query
                },{
                    skipNull: true
                })

                return axios({
                    method: 'GET',
                    url: apiUrl
                })
                    .then(res => {
                        this.users = res.data.data
                        this.links = res.data.links
                        this.meta = res.data.meta
                        return Promise.resolve(res.data)
                    })
            },
            changeList(){
                this.getUsersData(this.$route.query)
            },
            setFilter(){
                console.log("input => ", this.filter)
                let filter = {...this.filter}
                console.log(cleanDeep(filter))

                this.$router.push({query: cleanDeep(filter)})
            },
        },
        watch:{
            '$route': 'changeList',
            page(value){
                let query = { ...this.$route.query }
                query.page = value
                this.$router.push({query: query})
            },
        }
    }
</script>
