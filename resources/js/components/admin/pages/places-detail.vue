<template>
    <div>
        <v-card>
            <v-card-title>
                {{ place.name }}
            </v-card-title>
            <v-card-subtitle>
                {{ place.description }}
            </v-card-subtitle>
                <v-tabs
                    centered
                >
                    <v-tab>정보 수정</v-tab>
                    <v-tab>추천글</v-tab>
                    <v-tab>태그</v-tab>
                </v-tabs>
        </v-card>
        <place-form
            :place="place"
            :mode="'UPDATE'"
        />
    </div>
</template>

<script>
    import axios from 'axios'
    import placeForm from './places-form'

    export default {
        name: 'places',
        components: {
            placeForm
        },
        data(){
            return {
                mode: 'UPDATE',
                xhr: {
                    status: 'ready'
                },
                place: {},
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
            putPlaceData(){
                this.xhr.status = 'loading'
                axios.put('//'+this.$routeList('admin.api.places.update', this.$route.params.place_id),{
                    name: this.place.name,
                    description: this.place.description,
                    latitude: this.place.latitude,
                    longitude: this.place.longitude,
                    thumbnail: this.place.Image,
                    type: this.place.type,
                    zip_code: this.place.zip_code,
                    address1: this.place.address1,
                    address2: this.place.address2,
                    phone_number: this.place.phone,
                    homepage: this.place.Url,
                })
                    .then( res => {
                        console.log( "SUCCESS => ", res.data)
                        this.setPlaceData(res.data.data)
                    })
                    .catch( error => {
                        console.error(error)
                    })
                    .finally(() => {
                        this.xhr.status = 'ready'
                    })
            },
            setPlaceData(response){
                this.place = response
                this.place.latitude = this.place.geoPoint.latitude
                this.place.longitude = this.place.geoPoint.longitude
                this.place.zip_code = this.place.address.zip_code
                this.place.address1 = this.place.address.address1
                this.place.address2 = this.place.address.address2
            }
        },
        created(){
            axios.get('//'+this.$routeList('admin.api.places.show', this.$route.params.place_id))
                .then(res => {
                    this.setPlaceData(res.data.data)
                })
                .catch( error => {
                    console.error(error)
                })
        },
        mounted() {
        }
    }
</script>
