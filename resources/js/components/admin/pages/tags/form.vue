<template>
    <div>
        <v-form ref="place">
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Name</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        required
                        label="Name"
                        v-model="tag.name"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Type</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-select
                        label="Type"
                        :items="tagTypes"
                        item-value="name"
                        item-text="label"
                        v-model="tag.type"
                    >
                        {{ tag.type }}
                    </v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Label</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        required
                        label="Label"
                        v-model="tag.label"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Description</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-textarea
                        label="Description"
                        v-model="tag.description"
                    />
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Icon</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        required
                        label="prefix"
                        v-model="tag.icon_prefix"
                    />
                    <v-text-field
                        required
                        label="name"
                        v-model="tag.icon_name"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Icon Preview</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    preview
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Icon</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        required
                        label="color"
                        v-model="tag.color"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>color Preview</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    color preview
                </v-col>
            </v-row>
            <v-divider/>
            <v-banner v-if="mode ==='POST'">
                <v-progress-linear
                    :active="xhrLoading"
                    :indeterminate="xhrLoading"
                    absolute
                    top
                    color="deep-purple accent-4"
                ></v-progress-linear>
                create new place data

                <template v-slot:actions>
                    <v-btn
                        text
                        color="deep-purple accent-4"
                        @click="postTagData"
                    >CREATE</v-btn>
                </template>
            </v-banner>
            <v-banner v-if="mode ==='UPDATE'">
                <v-progress-linear
                    :active="xhrLoading"
                    :indeterminate="xhrLoading"
                    absolute
                    top
                    color="deep-purple accent-4"
                ></v-progress-linear>
                update tag data

                <template v-slot:actions>
                    <v-btn
                        text
                        color="deep-purple accent-4"
                        @click="putTagData"
                    >UPDATE</v-btn>
                </template>
            </v-banner>
        </v-form>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'placesForm',
        props: {
            tag: {
                default: function () {
                    return {}
                }
            },
            mode: {
                type: String,
                require: true,
            }
        },
        data(){
            return {
                xhr: {
                    status: 'ready'
                },
                tagTypes: []
            }
        },
        computed: {
            xhrLoading(){
                return this.xhr.status === 'loading'
            }
        },
        methods: {
            goUrl(link){
                window.open(link,'_blank')
            },
            setTagData(){
                return {
                    name:           this.tag.name,
                    label:          this.tag.label,
                    type:           this.tag.type,
                    description:    this.tag.description,
                    icon_prefix:    this.tag.icon_prefix,
                    icon_name:      this.tag.icon_name,
                    color:          this.tag.color,
                }
            },
            postTagData(){
                this.xhr.status = 'loading'
                axios.post('//'+this.$routeList('admin.api.tags.store'),this.setTagData())
                    .then( res => {
                        console.log("SUCCESS => ", res.data, res.data.id)
                        this.$router.push({name: 'Tags.Show', params: { tag_id: res.data.id }})
                    })
            },
            putTagData(){
                this.xhr.status = 'loading'
                console.log(this.$routeList('admin.api.tags.update', this.$route.params.tag_id))
                axios.put('//'+this.$routeList('admin.api.tags.update', this.$route.params.tag_id),this.setTagData())
                    .then( res => {
                        console.log( "SUCCESS => ", res.data)
                        this.updateTagData(res.data)
                    })
                    .catch( error => {
                        console.error(error)
                    })
                    .finally(() => {
                        this.xhr.status = 'ready'
                    })
            },
            updateTagData(tag){
                this.$emit("getNewTagData",tag)
            }
        },
        created(){
            axios.get('//'+this.$routeList('admin.api.tags.types.index'))
                .then( res => {
                    this.tagTypes = res.data
                })
        },
        mounted() {
        }
    }
</script>
