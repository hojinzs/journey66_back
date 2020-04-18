<script>
    import queryString from "query-string"
    import axios from "axios"
    import cleanDeep from "clean-deep"

    export default {
        name: 'LaravelResourceController',
        data(){
            return {
                xhr: {
                    method: 'GET',
                    status: 'ready',
                    url: null,
                },
                data: [],  // structure of laravel resource response
                links: {}, // structure of laravel resource response
                meta: { // structure of laravel resource response
                    current_page: null,
                    from: 1,
                    last_page: 1,
                    path: null,
                    per_page: 10,  //default (10)
                    to: 1,
                    total: 1
                },
                filter: {},  // page filter
                page: 1,
                per_page: 10
            }
        },
        computed: {
            xhrLoading(){
                return this.xhr.status === 'loading'
            }
        },
        methods: {
            async getData(querystring){
                if(this.meta.path === null) console.error("set ajax url first. call setXhr() first.")

                let query = {}
                if(typeof querystring === 'undefined'){
                    query = {...this.$route.query}
                } else {
                    query = querystring
                }

                if(this.per_page !== 10){
                    query.per_page = this.per_page
                }

                let apiUrl = queryString.stringifyUrl({
                    url: this.meta.path,
                    query: query
                },{
                    skipNull: true
                })

                this.xhr.status = 'loading'
                return axios({
                    method: this.xhr.method,
                    url: apiUrl,
                })
                    .then(res => {
                        this.users = res.data.data
                        this.links = res.data.links
                        this.meta = res.data.meta

                        console.log('Page => ', this.page, this.meta.current_page)
                        if(this.meta.current_page !== this.page){
                            this.page = this.meta.current_page;
                        }

                        return Promise.resolve(res.data)
                    })
                    .finally(() => {
                        this.xhr.status = 'ready'
                    })
            },
            changeList(){
                this.getData(this.$route.query)
            },
            setFilter(){
                if(this.filter === {}) return false
                this.$router.push({query: cleanDeep({...this.filter})})
            },
            setXhr(method = 'GET',url){
                this.xhr.status = method;
                this.xhr.status = url;
                this.meta.path = url;
            },
            setFilterValueByQuery(){
                let params = Object.keys(this.filter)
                params.forEach( param => {
                    if(this.$route.query[ param ]){
                        this.filter[ param ] = this.$route.query[ param ]
                    }
                })
            }
        },
        watch:{
            '$route': 'changeList',
            page(value){
                if(value !== this.meta.current_page){
                    let query = { ...this.$route.query }
                    query.page = value
                    this.$router.push({query: query})
                }
            },
        }
    }
</script>
