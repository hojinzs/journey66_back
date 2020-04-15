import Vue from 'vue'
import router from './plugins/admin-route'
import store from './store/index'
import vuetify from "./plugins/vuetify"
import App from './components/admin/app'
import axios from 'axios'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

new Vue({
    store,
    router,
    vuetify,
    el: '#app',
    render: h => h(App),
})
