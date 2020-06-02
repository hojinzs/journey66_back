<template>
    <v-form>
        <validation-observer tag="div" ref="place">
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
                    <validation-provider name="name" v-slot="{ errors }">
                        <v-text-field
                            required
                            label="Name"
                            v-model="place.name"
                            :error-messages="errors"
                        />
                    </validation-provider>
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
                    <validation-provider name="type" v-slot="{ errors }">
                        <v-select
                            label="Type"
                            :items="placeTypes"
                            item-text="label"
                            :return-object="true"
                            v-model="place.placeType"
                            :loading="placeTypes === null"
                            :error-messages="errors"
                        >
                        </v-select>
                    </validation-provider>
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
                    <validation-provider name="description" v-slot="{ errors }">
                        <v-textarea
                            label="Description"
                            :error-messages="errors"
                            v-model="place.description"
                        />
                    </validation-provider>
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
                        <validation-provider name="latitude" v-slot="{ errors }">
                            <v-text-field
                                required
                                label="latitude"
                                v-model="place.latitude"
                                :error-messages="errors"
                            />
                        </validation-provider>
                    </div>
                    <div>
                        <validation-provider name="longitude" v-slot="{ errors }">
                            <v-text-field
                                required
                                label="longitude"
                                v-model="place.longitude"
                                :error-messages="errors"
                            />
                        </validation-provider>
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
                    <validation-provider name="phone_number" v-slot="{ errors }">
                        <v-text-field
                            label="phone number"
                            v-model="place.phone"
                        />
                    </validation-provider>
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
                        <validation-provider name="zip_code" v-slot="{ errors }">
                            <v-text-field
                                label="zip_code"
                                v-model="place.zip_code"
                            />
                        </validation-provider>
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
                    <validation-provider name="homepage" v-slot="{ errors }">
                        <v-text-field
                            label="Url"
                            v-model.lazy="place.Url"
                            :error-messages="errors"
                        />
                    </validation-provider>
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
                        <validation-provider name="thumbnail" v-slot="{ errors }">
                            <v-text-field
                                label="Image"
                                v-model.lazy="place.Image"
                                :error-messages="errors"
                            />
                        </validation-provider>
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
                        <v-img :src="place.Image"/>
                    </div>
                </v-col>
            </v-row>
            <v-divider/>
            <v-banner>
                <span v-if="mode==='POST'">
                    create new place data
                </span>
                <span v-if="mode==='UPDATE'">
                    update place data
                </span>

                <template v-slot:actions>
                    <span class="red--text" v-if="xhr.error">
                        <v-icon>mdi-alert</v-icon>
                        {{ xhr.error }}
                    </span>
                    <v-btn v-if="mode==='POST'"
                           text
                           color="deep-purple accent-4"
                           @click="postPlaceData"
                           :loading="xhrLoading"
                    >CREATE</v-btn>
                    <v-btn v-if="mode==='UPDATE'"
                           text
                           color="deep-purple accent-4"
                           @click="putPlaceData"
                           :loading="xhrLoading"
                    >UPDATE</v-btn>
                </template>
            </v-banner>
        </validation-observer>
        <v-snackbar
            v-model="snackbar.show"
            :timeout="2000"
        >
            {{ snackbar.message }}
            <v-btn
                color="blue"
                text
                @click="snackbar.show = false"
            >
                Close
            </v-btn>
        </v-snackbar>
    </v-form>
</template>

<script>
    import axios from 'axios'

    export default {
        name: 'placesForm',
        props: {
            place: {
                default: function () {
                    return {
                        placeType: {
                            name: null,
                            label: null,
                        }
                    }
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
                    status: 'ready',
                    error: null,
                },
                placeTypes: [],
                snackbar: {
                    show: false,
                    message: "update complete!"
                }
            }
        },
        computed: {
            xhrLoading(){
                return this.xhr.status === 'loading'
            },
        },
        methods: {
            goUrl(link){
                window.open(link,'_blank')
            },
            async loadPlaceType(){
                await axios.get('//'+this.$routeList('admin.api.places.types.index'))
                        .then( res => {
                            this.placeTypes = res.data
                        })
            },
            setPlaceData(){
                return {
                    name:           this.place.name,
                    description:    this.place.description,
                    latitude:       this.place.latitude,
                    longitude:      this.place.longitude,
                    thumbnail:      this.place.Image,
                    type:           this.place.placeType.name,
                    zip_code:       this.place.zip_code,
                    address1:       this.place.address1,
                    address2:       this.place.address2,
                    phone_number:   this.place.phone,
                    homepage:       this.place.Url,
                }
            },
            postPlaceData(){
                this.xhr.status = 'loading'
                this.xhr.error = null

                axios.post('//'+this.$routeList('admin.api.places.store'),this.setPlaceData())
                    .then( res => {
                        this.$router.push({name: 'Places.show', params: { place_id: res.data.data.id }})
                    })
                    .catch( error => {
                        if(error.response.status === 422){
                            this.$refs.place.setErrors(error.response.data.errors);
                            this.xhr.error = error.response.data.message;
                        }
                    })
                    .finally(() => {
                        this.xhr.status = 'ready'
                    })
            },
            putPlaceData(){
                this.xhr.status = 'loading'
                this.xhr.error = null

                axios.put('//'+this.$routeList('admin.api.places.update', this.$route.params.place_id),this.setPlaceData())
                    .then( res => {
                        this.updatePlaceData(res.data)
                        this.snackbar.show = true
                    })
                    .catch( error => {
                        if(error.response.status === 422){
                            this.$refs.place.setErrors(error.response.data.errors);
                            this.xhr.error = error.response.data.message;
                        }
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
            this.loadPlaceType()
        },
        mounted() {
        }
    }
</script>
