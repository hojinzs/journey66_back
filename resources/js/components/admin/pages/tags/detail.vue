<template>
    <div>
        <v-card>
            <v-card-title>
                {{ tag.name }}
            </v-card-title>
            <v-card-subtitle>
                {{ tag.description }}
            </v-card-subtitle>
                <v-tabs
                    centered
                >
                    <v-tab>정보 수정</v-tab>
                </v-tabs>
        </v-card>
        <tag-form
            :tag="tag"
            :mode="'UPDATE'"
        />
    </div>
</template>

<script>
    import axios from 'axios'
    import tagForm from './form'

    export default {
        name: 'tags',
        components: {
            tagForm
        },
        data(){
            return {
                mode: 'UPDATE',
                xhr: {
                    status: 'ready'
                },
                tag: {},
            }
        },
        computed: {
            xhrLoading(){
                return this.xhr.status === 'loading'
            }
        },
        methods: {
            setTagData(response){
                this.tag = response
            }
        },
        created(){
            axios.get('//'+this.$routeList('admin.api.tags.show', this.$route.params.tag_id))
                .then(res => {
                    this.setTagData(res.data)
                })
                .catch( error => {
                    console.error(error)
                })
        },
        mounted() {
        }
    }
</script>
