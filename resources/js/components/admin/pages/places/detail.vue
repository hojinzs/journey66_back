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
    import placeForm from './form'

    export default {
        name: 'places',
        components: {
            placeForm
        },
        data(){
            return {
                place: {},
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
