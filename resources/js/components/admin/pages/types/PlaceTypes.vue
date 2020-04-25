<template>
    <div>
        <div>
            <v-simple-table>
                <template v-slot:default>
                    <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            name
                        </th>
                        <th>
                            label
                        </th>
                        <th>
                            description
                        </th>
                        <th>
                            places_count
                        </th>
                        <th>
                            action
                        </th>
                    </tr>
                    </thead>
                    <tr
                        v-for="(placeType, index) in placeTypes"
                        :class="{
                                'modified': ( placeType.$mode === 'update' && placeType.$changed ),
                                'delete': placeType.$mode === 'delete',
                                'insert' : placeType.$mode === 'insert',
                            }"
                    >
                        <td>
                            {{ placeType.id || '등록' }}
                        </td>
                        <td>
                            <v-text-field
                                v-if="placeType.$mode === 'insert' "
                                v-model="placeType.name"
                            />
                            <span
                                v-else
                            >
                                    {{ placeType.name }}
                                </span>
                        </td>
                        <td>
                            <v-text-field
                                v-model="placeType.label"
                                :disabled="placeType.$mode === 'delete'"
                            />
                        </td>
                        <td>
                            <v-textarea
                                v-model="placeType.description"
                                :disabled="placeType.$mode === 'delete'"
                                rows="2"
                            />
                        </td>
                        <td>
                            {{ placeType.places_count }}
                        </td>
                        <td>
                            <v-btn
                                @click="LRC_restoreItem(placeType)"
                                :disabled="placeType.$mode !== 'update'"
                            >
                                <v-icon>mdi-backup-restore</v-icon>
                            </v-btn>
                            <v-btn
                                @click="LRC_deleteItem(placeType)"
                                :disabled="placeType.places_count > 0"
                            >
                                <v-icon v-if="placeType.$mode !== 'delete'">mdi-delete</v-icon>
                                <v-icon v-else>mdi-restore</v-icon>
                            </v-btn>
                        </td>
                    </tr>
                </template>
            </v-simple-table>
        </div>
        <div>
            <v-progress-linear
                :active="loading"
                :indeterminate="loading"
                absolute
                bottom
                color="deep-purple accent-4"
            ></v-progress-linear>
            <v-btn
                @click="LRC_insertItem"
            >
                New
            </v-btn>
            <v-btn
                @click="sendData"
            >
                Submit
            </v-btn>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import ListResourceController from "../../../../mixin/ListResourceController";

    export default {
        name: "PlaceTypes",
        mixins: [ ListResourceController ],
        data() {
            return {
                placeTypes: [],
                placeTypeModel: {
                    'name': null,
                    'label': null,
                    'description': null,
                    'places_count': null,
                },
                loading: false,
            }
        },
        methods: {
            getPlaceTypeData(){
                axios.get('//'+this.$routeList('admin.api.places.types.index'))
                    .then(res => {
                        this.placeTypes = this.LRC_initialize(this.placeTypeModel,res.data)
                    })
            },
            async sendData(){
                let sendList = []
                this.placeTypes.forEach(placeType => {
                    if(placeType.$mode === 'update' && placeType.$changed === false){
                        return
                    }
                    sendList.push(placeType)
                })

                console.log("send List =>", sendList);

                this.loading = true
                for(let i = 0; i < sendList.length; i++){
                    await this.setPlaceTypeData(sendList[i],sendList[i].$mode)
                        .then(() => console.log("SEND => ", i));
                }
                this.loading = false
            },
            setPlaceTypeData(placeType, mode){
                let method = {
                    'insert': {
                        name: 'store',
                        method: "POST"
                    },
                    'update': {
                        name: 'update',
                        method: 'PUT'
                    },
                    'delete': {
                        name: 'destroy',
                        method: 'DELETE',
                    },
                }
                let id = placeType.id || null
                let apiUrl = '//'+this.$routeList('admin.api.places.types.'+method[mode].name, id)

                // send data
                return axios({
                    url: apiUrl,
                    method: method[mode].method,
                    data: {
                        name: placeType.name,
                        label: placeType.label,
                        description: placeType.description,
                    }
                })
                    .then( res => {
                        if(mode === 'delete'){
                            this.LRC_destroyItem(placeType)
                        } else {
                            this.LRC_updateOrigin(placeType, res.data)
                        }
                    })
                    .catch( error => { console.error(error) })
            }
        },
        created() {
            this.getPlaceTypeData();
        }
    }
</script>

<style lang="css" scoped>
    .delete{
        background-color: #f44336;
    }
    .modified{
        background-color: #00acc1;
    }
    .insert{
        background-color: #00e676;
    }
</style>
