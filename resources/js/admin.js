import Vue from 'vue'
import router from './plugins/admin-route'
import store from './store/index'
import vuetify from "./plugins/vuetify"
import App from './components/admin/app'
import axios from 'axios'
import Cookies from 'js-cookie'
import routeList from './plugins/route-list'
import routes from './config/routes'
import veeValidate from './plugins/veeValidate'
import loader from './plugins/loader'

Vue.use(routeList,{routeJson: routes})

adminAuthorization()
    .then(() => {
        new Vue({
            apiRouter: routeList,
            store,
            router,
            vuetify,
            veeValidate,
            loader,
            el: '#app',
            render: h => h(App),
        })
    })
    .catch(() => {
        location.href="/login"
    })

async function adminAuthorization() {

    if(Cookies.get('Authorization')){
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
        axios.defaults.headers.common['Authorization'] = `Bearer ${Cookies.get('Authorization')}`
        await Promise.resolve()
    } else {
        await Promise.reject('not found Authorization Cookie')
    }
}
