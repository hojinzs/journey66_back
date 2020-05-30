<template>
    <v-row>
        <v-col cols="12" md="2">
            <b>Tables</b>
            <v-radio-group v-model="tableSelected">
                <v-radio v-for="(table,index) in tables"
                         :key="index"
                         :label="table"
                         :value="table"
                />
            </v-radio-group>
        </v-col>
        <v-col cols="12" md="2">
            <b>Columns</b>
            <v-radio-group v-model="columnSelected">
                <v-radio v-for="(column,index) in selectedTableColumns"
                         :key="index"
                         :label="column.column"
                         :value="column.column"
                />
            </v-radio-group>
        </v-col>
        <v-col cols="12" md="8">
            <b>Options</b>
            <div v-if="options.length > 0">
                <v-simple-table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>option</th>
                            <th>label</th>
                            <th>ordering</th>
                            <th>actions</th>
                        </tr>
                        <template v-for="(option,index) in optionList">
                            <tr>
                                <td>
                                    {{index}}
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="option.option"
                                    />
                                </td>
                                <td>
                                    <v-text-field
                                        v-model="option.label"
                                    />
                                </td>
                                <td>
                                    1
                                </td>
                                <td>
                                    <v-btn
                                        :disabled="( option.mode !== 'update' || option.$changed !== true )"
                                        @click="option.restoreChanges"
                                    >
                                        <v-icon>mdi-backup-restore</v-icon>
                                    </v-btn>
                                    <v-btn
                                        :disabled="option.tags_count > 0"
                                        @click="deleteOption(option)"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <v-btn
                                        @click="updateOrInsert(option)"
                                    >
                                        <v-icon v-if="option.$ajax._status === 'error'">
                                            mdi-alert
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-cloud-upload
                                        </v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </template>
                    </thead>
                </v-simple-table>
                <v-btn
                    @click="LRC_insertItem"
                >
                    New
                </v-btn>
            </div>
            <div v-else>
                Select Table And Column
            </div>
        </v-col>
    </v-row>
</template>

<script>
    import axios from 'axios'
    import ListResourceController from "../../../../mixin/ListResourceController";

    export default {
        name: "options",
        mixins: [ ListResourceController ],
        data(){
            return {
                tables: [
                    'tags'
                ],
                tableColumns: {},
                options: [],
                optionModel: {
                    option: '',
                    label: '',
                },
                tableSelected: null,
                columnSelected: null,
            }
        },
        computed:
        {
            selectedTableColumns(){
                if(this.tableSelected === null){
                    return []
                }

                return this.tableColumns[this.tableSelected]
            },
            optionList(){
                return this.options
            }
        },
        methods:
        {
            addColumns(table, columns){
                if(typeof this.tableColumns[table] === 'undefined'){
                    this.tableColumns[table] = columns
                } else {
                    columns.forEach(column => this.tableColumns.push(column))
                }
            },
            async getOptionsIndex(table){
                await axios.get('//'+this.$routeList('admin.api.options.index',table),)
                    .then( res => {
                        this.addColumns(table, res.data)
                    })
            },
            async getOptions(table,column){
                await axios.get('//'+this.$routeList('admin.api.options.show',table,column))
                    .then( res => {
                        this.options = this.LRC_initialize(this.optionModel, res.data)
                    })
            },
            updateOrInsert(option){
                if(option.$mode === 'insert'){
                    console.log("OPTION insert")
                    this.insertOption(option)
                } else if(option.$mode === 'update'){
                    console.log("OPTION update")
                }
            },
            async insertOption(option){
                await axios.post('//'+this.$routeList('admin.api.options.store',this.tableSelected,this.columnSelected),{
                    option: option.option,
                    label: option.label,
                })
                    .then( res => {
                        this.LRC_updateOrigin(option,res.data)
                    })
            },
            async deleteOption(option){
                let opt = option.$origin

                await axios.delete('//'+this.$routeList('admin.api.options.destroy',opt.table,opt.column,opt.option))
                    .then(() => {
                        this.LRC_destroyItem(option)
                    })
            }
        },
        watch:{
            columnSelected(val){
                if(val === null){
                    this.options = []
                }
                this.getOptions(this.tableSelected, val)
            }
        },
        created()
        {
            this.tables.forEach( table => this.getOptionsIndex(table))
        },
    }
</script>
