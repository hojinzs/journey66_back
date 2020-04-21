<template>
    <div>
        <v-card>
            <v-card-title>
                {{ user.name }}
            </v-card-title>
            <v-card-subtitle>
                {{ user.email }}
            </v-card-subtitle>
                <v-tabs
                    centered
                >
                    <v-tab>정보 수정</v-tab>
                </v-tabs>
        </v-card>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'tags',
        data(){
            return {
                mode: 'UPDATE',
                xhr: {
                    status: 'ready'
                },
                user: {},
            }
        },
        computed: {
            xhrLoading(){
                return this.xhr.status === 'loading'
            }
        },
        methods: {
            setUserData(response){
                this.user = response
            }
        },
        created(){
            axios.get('//'+this.$routeList('admin.api.users.show', this.$route.params.user_id))
                .then(res => {
                    this.setUserData(res.data)
                })
                .catch( error => {
                    console.error(error)
                })
        },
        mounted() {
        }
    }
</script>
