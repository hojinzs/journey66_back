<template>
    <div>
        <v-expansion-panels>
            <v-expansion-panel>
                <v-expansion-panel-header>NEW OPTION</v-expansion-panel-header>
                <v-expansion-panel-content>
                    <createOption/>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
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
                            <th>order</th>
                            <th>option</th>
                            <th>label</th>
                            <th>show</th>
                            <th>actions</th>
                        </tr>
                        <template v-for="(option, index) in optionList">
                            <validation-observer tag="tr" :ref="'obj_'+option.id">
                                <td>
                                    {{ option.id }}
                                </td>
                                <td>
                                <span v-if="option.order">
                                    <v-btn :disabled="option.order === 1"
                                           @click="orderSwap(option, optionList[index - 1])"
                                           :x-small="true"
                                    >
                                        <v-icon>mdi-arrow-up</v-icon>
                                    </v-btn>
                                    <v-btn :disabled="option.order === lastOrderNumber"
                                           @click="orderSwap(option, optionList[index + 1])"
                                           :x-small="true"
                                    >
                                        <v-icon>mdi-arrow-down</v-icon>
                                    </v-btn>
                                </span>
                                </td>
                                <td>
                                    <validation-provider v-slot="{ errors }" name="option">
                                        <v-text-field v-model="option.option"
                                                      label="option"
                                                      :error-messages="errors"
                                        />
                                    </validation-provider>
                                </td>
                                <td>
                                    <validation-provider v-slot="{ errors }" name="label">
                                        <v-text-field v-model="option.label"
                                                      label="label"
                                                      :error-messages="errors"
                                        />
                                    </validation-provider>
                                </td>
                                <td>
                                <span v-if="option.order">
                                    {{ option.order }}
                                </span>
                                    <span v-else>
                                    -
                                </span>
                                    <v-btn
                                        @click="toggleShow(option)"
                                        :x-small="true"
                                    >
                                    <span v-if="option.show === 1">
                                        hide
                                    </span>
                                        <span v-if="option.show === 0">
                                        show
                                    </span>
                                    </v-btn>
                                </td>
                                <td>
                                    <v-btn
                                        :disabled="( option.$mode !== 'update' || option.$changed !== true )"
                                        @click="LRC_restoreItem(option)"
                                        :x-small="true"
                                    >
                                        <v-icon>mdi-backup-restore</v-icon>
                                    </v-btn>
                                    <v-btn
                                        :disabled="option.tags_count > 0"
                                        @click="deleteOption(option)"
                                        :x-small="true"
                                    >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                    <v-btn
                                        @click="updateOption(option)"
                                        :x-small="true"
                                    >
                                        <v-icon v-if="option.$ajax._status === 'error'">
                                            mdi-alert
                                        </v-icon>
                                        <v-icon v-else>
                                            mdi-cloud-upload
                                        </v-icon>
                                    </v-btn>
                                </td>
                            </validation-observer>
                        </template>
                        <tr>
                            <td>
<!--                                <v-btn-->
<!--                                    @click="LRC_insertItem"-->
<!--                                >New</v-btn>-->
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <v-btn
                                    @click="putOrder"
                                    :loading="loading"
                                >Submit</v-btn>
                            </td>
                            <td></td>
                        </tr>
                        </thead>
                    </v-simple-table>
                </div>
                <div v-else>
                    Select Table And Column
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import axios from 'axios'
    import ListResourceController from "../../../../mixin/ListResourceController";
    import createOption from "./create"

    let beforeInterceptor;
    let afterInterceptor;

    export default {
        name: "options",
        mixins: [ ListResourceController ],
        components: {
            createOption
        },
        data(){
            return {
                tables: [],
                tableColumns: {},
                options: [],
                optionModel: {
                    order: '',
                    show: 0,
                    option: '',
                    label: '',
                },
                tableSelected: null,
                columnSelected: null,
                loading: false,
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
                if(this.options.length < 2){
                    return this.options
                }

                let arr1 = []
                let arr2 = []
                this.options.forEach(option => {
                    if(option.show === 0){
                        arr2.push(option)
                    } else {
                        arr1.push(option)
                    }
                })

                arr1.sort( (a,b) => {
                    return a.order - b.order
                })

                return arr1.concat(arr2)
            },
            lastOrderNumber() {
                return this.options.filter( option => option.show ).length
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
            toggleShow(option){
                if(option.show === 0){
                    option.show = 1
                    option.order = this.lastOrderNumber
                } else {
                    option.show = 0
                    if(option.order === this.lastOrderNumber + 1){
                        option.order = null
                    } else {
                        let start = option.order
                        let lastOrder = this.lastOrderNumber + 1
                        this.orderUpShift(start, lastOrder, option)
                        option.order = null
                    }
                }
            },
            orderOption(option, order){
                switch (order) {
                    case 'up':
                        break;
                    case 'down':
                        break;
                }
            },
            orderSwap(first, second){
                let a = first.order
                let b = second.order

                first.order = b
                second.order = a

            },
            orderDownShift(start,end,ignore = null){
                this.options.forEach( option => {
                    if(option.order <= start && option.order >= end && option !== ignore){
                        option.order = option.order + 1
                    }
                })
            },
            orderUpShift(start, end, ignore = null){
                this.options.forEach( option => {
                    if(option.order >= start && option.order <= end && option !== ignore){
                        option.order = option.order - 1
                    }
                })
            },
            async getTables(){
                await axios.get('//'+this.$routeList('admin.api.options.list'))
                    .then(res => {
                        this.tables = res.data
                        console.log("get Tables => ", this.tables)
                    })
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
            // updateOrInsert(option){
            //     if(option.$mode === 'insert'){
            //         this.insertOption(option)
            //         console.log("OPTION insert")
            //     } else if(option.$mode === 'update'){
            //         this.updateOption(option)
            //         console.log("OPTION update")
            //     }
            // },
            // async insertOption(option){
            //     await axios.post('//'+this.$routeList('admin.api.options.store',this.tableSelected,this.columnSelected),{
            //         option: option.option,
            //         label: option.label,
            //     })
            //         .then( res => {
            //             this.LRC_updateOrigin(option,res.data)
            //         })
            // },
            async updateOption(option){
                option.$ajax.setSending()
                await axios.put('//'+this.$routeList('admin.api.options.update', this.tableSelected, this.columnSelected, option.id),{
                    option: option.option,
                    label: option.label,
                })
                    .then( res => {
                        this.LRC_updateOrigin(option, res.data)
                    })
                    .catch( error => {
                        if(error.response.status === 422){
                            let refs = this.$refs['obj_'+option.id][0];
                            console.log("Objects => ", 'obj_'+option.id, refs)

                            refs.setErrors(error.response.data.errors)
                        }
                    })
                    .finally(() => {
                        option.$ajax
                    })
            },
            async deleteOption(option){
                let opt = option.$origin

                await axios.delete('//'+this.$routeList('admin.api.options.destroy',opt.table,opt.column,opt.option))
                    .then(() => {
                        this.LRC_destroyItem(option)
                    })
            },
            async putOrder(){

                let order = this.optionList.reduce((acc,option) => {
                    console.log("ACC => ", acc, option.option)
                    if(option.show === 1){
                        acc.push(option.option)
                    }
                    return acc
                },[])

                await axios.put('//'+this.$routeList('admin.api.options.order',this.tableSelected,this.columnSelected),{
                    'order': order
                })
                    .then( res => {
                        this.options = this.LRC_initialize(this.optionModel, res.data)
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
            beforeInterceptor = axios.interceptors.request.use(
                config => {
                    this.loading = true
                    return config
                })

            afterInterceptor = axios.interceptors.response.use(
                    response => {
                        this.loading = false
                        return response;
                    },
                    error => {
                        this.loading = false
                        return Promise.reject(error);
                    });

            this.getTables()
                .then(() => {
                    this.tables.forEach( table => this.getOptionsIndex(table))
                })
        },
        beforeDestroy() {
            axios.interceptors.request.eject(beforeInterceptor);
            axios.interceptors.response.eject(afterInterceptor);
        }
    }
</script>
