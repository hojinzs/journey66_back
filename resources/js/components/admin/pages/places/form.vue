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
                        v-model="place.name"
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
                        :items="placeTypes"
                        v-model="place.type"
                    >
                        {{ place.type }}
                    </v-select>
                </v-col>
            </v-row>
            <v-row>
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
                        label="Name"
                        v-model="place.description"
                    />
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>geoPoint</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <div>
                        <v-text-field
                            required
                            label="latitude"
                            v-model="place.latitude"
                        />
                    </div>
                    <div>
                        <v-text-field
                            required
                            label="longitude"
                            v-model="place.longitude"
                        />
                    </div>
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>preview</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <div>
                        map
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>phone</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        label="phone number"
                        v-model="place.phone"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Address</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <div>
                        <v-text-field
                            label="zip_code"
                            v-model="place.zip_code"
                        />
                    </div>
                    <div>
                        <v-text-field
                            label="address1"
                            v-model="place.address1"
                        />
                    </div>
                    <div>
                        <v-text-field
                            label="address2"
                            v-model="place.address2"
                        />
                    </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Url</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-text-field
                        label="Url"
                        v-model.lazy="place.Url"
                    />
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <v-btn @click="goUrl(place.Url)">Link Test</v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Image</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <div>
                        <v-text-field
                            label="Image"
                            v-model.lazy="place.Image"
                        />
                    </div>
                </v-col>
                <v-col
                    cols="12"
                    md="2"
                >
                    <v-subheader>Preview</v-subheader>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                >
                    <div>
                        <v-img :src="place.Image" />
                    </div>
                </v-col>
            </v-row>
            <v-divider/>
            <v-banner v-if="mode=='POST'">
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
                        @click="postPlaceData"
                    >CREATE</v-btn>
                </template>
            </v-banner>
            <v-banner v-if="mode=='UPDATE'">
                <v-progress-linear
                    :active="xhrLoading"
                    :indeterminate="xhrLoading"
                    absolute
                    top
                    color="deep-purple accent-4"
                ></v-progress-linear>
                update place data

                <template v-slot:actions>
                    <v-btn
                        text
                        color="deep-purple accent-4"
                        @click="putPlaceData"
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
            place: {
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
                placeTypes: [
                    'lockstand', 'abc', 'def'
                ]
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
            setPlaceData(){
                return {
                    name:           this.place.name,
                    description:    this.place.description,
                    latitude:       this.place.latitude,
                    longitude:      this.place.longitude,
                    thumbnail:      this.place.Image,
                    type:           this.place.type,
                    zip_code:       this.place.zip_code,
                    address1:       this.place.address1,
                    address2:       this.place.address2,
                    phone_number:   this.place.phone,
                    homepage:       this.place.Url,
                }
            },
            postPlaceData(){
                this.xhr.status = 'loading'
                axios.post('//'+this.$routeList('admin.api.places.store'),this.setPlaceData())
                    .then( res => {
                        console.log("SUCCESS => ", res.data, res.data.data.id)
                        this.$router.push({name: 'Places.show', params: { place_id: res.data.data.id }})
                    })
            },
            putPlaceData(){
                this.xhr.status = 'loading'
                axios.put('//'+this.$routeList('admin.api.places.update', this.$route.params.place_id),this.setPlaceData())
                    .then( res => {
                        console.log( "SUCCESS => ", res.data)
                        this.updatePlaceData(res.data)
                    })
                    .catch( error => {
                        console.error(error)
                    })
                    .finally(() => {
                        this.xhr.status = 'ready'
                    })
            },
            updatePlaceData(place){
                this.$emit("getNewPlaceData",place)
            }
        },
        created(){
        },
        mounted() {
        }
    }
</script>
