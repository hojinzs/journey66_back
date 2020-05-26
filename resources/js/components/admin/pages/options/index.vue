<template>
    <div>
        <div>
            {{ tables }}
        </div>
        <div>
            {{ groupByTables }}
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    export default {
        name: "options",
        data(){
            return {
                tables: [
                    'tags'
                ],
                options: []
            }
        },
        computed:
        {
            groupByTables(){
                return this.options
            }
        },
        methods:
        {
            getOptionsIndex(){
                console.log("get Options")

                this.tables.forEach( option => {
                    console.log(this.$routeList('admin.api.options.index', option), option)

                    axios.get('//'+this.$routeList('admin.api.options.index',option),)
                        .then( res => {
                            this.options.push(res.data)
                        })
                })
            }
        },
        created()
        {
            this.getOptionsIndex()
        },
    }
</script>

<style scoped>

</style>
