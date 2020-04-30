<template>
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
                        tags
                    </th>
                    <th>
                        action
                    </th>
                </tr>
                </thead>
                <template
                    v-for="(tagType, index) in tagTypes"
                >
                    <tr
                        :class="{
                            'modified': ( tagType.$mode === 'update' && tagType.$changed ),
                            'delete': tagType.$mode === 'delete',
                            'insert' : tagType.$mode === 'insert',
                        }"
                    >
                        <td>
                            {{ tagType.id || '등록' }}
                        </td>
                        <td>
                            <v-text-field
                                v-if="tagType.$mode === 'insert' "
                                v-model="tagType.name"
                            />
                            <span
                                v-else
                            >
                                {{ tagType.name }}
                            </span>
                        </td>
                        <td>
                            <v-text-field
                                v-model="tagType.label"
                                :disabled="tagType.$mode === 'delete'"
                            />
                        </td>
                        <td>
                            <v-textarea
                                v-model="tagType.description"
                                :disabled="tagType.$mode === 'delete'"
                                rows="2"
                            />
                        </td>
                        <td>
                            {{ tagType.tags_count }}
                        </td>
                        <td>
                            <v-btn
                                :disabled="( tagType.$mode !== 'update' || tagType.$changed !== true )"
                                @click="LRC_restoreItem(tagType)"
                            >
                                <v-icon>mdi-backup-restore</v-icon>
                            </v-btn>
                            <v-btn
                                :disabled="tagType.tags_count > 0"
                                @click="LRC_deleteItem(tagType)"
                            >
                                <v-icon v-if="tagType.$mode !== 'delete'">mdi-delete</v-icon>
                                <v-icon v-else>mdi-restore</v-icon>
                            </v-btn>
                            <v-btn
                                :disabled="!( tagType.$mode === 'insert' || tagType.$mode === 'delete' || tagType.$changed === true )"
                                :loading="( tagType.$ajax._status === 'sending' || ( tagType.$changed === true && loading === true) )"
                                @click="setTagTypeData(tagType,tagType.$mode)"
                            >
                                <v-icon v-if="tagType.$ajax._status === 'error'">
                                    mdi-alert
                                </v-icon>
                                <v-icon v-else>
                                    mdi-cloud-upload
                                </v-icon>
                            </v-btn>
                        </td>
                    </tr>
                    <tr
                        v-if="tagType.$ajax._status !== 'pending'"
                        :class="{
                            'modified': ( tagType.$mode === 'update' && tagType.$changed ),
                            'delete': tagType.$mode === 'delete',
                            'insert' : tagType.$mode === 'insert',
                        }"
                    >
                        <td colspan="6">
                            <span
                                v-show="tagType.$ajax._status === 'error'"
                            >
                                <v-icon>mdi-alert</v-icon> {{ tagType.$ajax._errorText }}
                            </span>
                            <v-progress-linear
                                :active="tagType.$ajax._status === 'sending'"
                                :indeterminate="tagType.$ajax._status === 'sending'"
                                color="deep-purple accent-4"
                            ></v-progress-linear>
                        </td>
                    </tr>
                </template>
            </template>
        </v-simple-table>
        <v-row>
            <v-col
                cols="12"
                md="6"
            >
                <v-btn
                    @click="LRC_insertItem"
                >
                    New
                </v-btn>
            </v-col>
            <v-col
                class="d-flex justify-md-end"
                cols="12"
                md="6"
            >
                <v-btn
                    :disabled="LRC_changedCount === 0"
                    @click="sendAllData"
                    color="deep-purple accent-4"
                >
                    Submit
                </v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import axios from 'axios';
    import ListResourceController from "../../../../mixin/ListResourceController";

    export default {
        name: "TagTypes",
        mixins: [ ListResourceController ],
        data() {
            return {
                tagTypes: [],
                tagTypeModel: {
                    'name': null,
                    'label': null,
                    'description': null,
                    'tags_count': null,
                },
                loading: false,
            }
        },
        methods: {
            getTagTypeData(){
                axios.get('//'+this.$routeList('admin.api.tags.types.index'))
                    .then(res => {
                        this.tagTypes = this.LRC_initialize(this.tagTypeModel,res.data)
                    })
            },
            async sendAllData(){
                let sendList = []
                this.tagTypes.forEach(tagType => {
                    if(tagType.$mode === 'update' && tagType.$changed === false){
                        return
                    }
                    sendList.push(tagType)
                })

                console.log("send List =>", sendList);

                this.loading = true
                for(let i = 0; i < sendList.length; i++){
                    await this.setTagTypeData(sendList[i],sendList[i].$mode)
                        .then(() => console.log("SEND => ", i));
                }
                this.loading = false
            },
            setTagTypeData(tagType, mode){
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
                let id = tagType.id || null
                let apiUrl = '//'+this.$routeList('admin.api.tags.types.'+method[mode].name, id)

                // send data
                tagType.$ajax.setSending();
                return axios({
                    url: apiUrl,
                    method: method[mode].method,
                    data: {
                        name: tagType.name,
                        label: tagType.label,
                        description: tagType.description,
                    }
                })
                    .then( res => {
                        tagType.$ajax._status = 'pending';
                        if(mode === 'delete'){
                            this.LRC_destroyItem(tagType)
                        } else {
                            this.LRC_updateOrigin(tagType, res.data)
                        }
                    })
                    .catch( error => {
                        tagType.$ajax.setError(error.response.data.message)
                    })
            }
        },
        created() {
            this.getTagTypeData();
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
