import Vue from 'vue'
import router from './plugins/admin-route'
import store from './store/index'
import vuetify from "./plugins/vuetify"
import App from './components/admin/app'
import axios from 'axios'
import Cookies from 'js-cookie'
import routeList from './plugins/route-list'
import routes from './config/routes'

Vue.use(routeList,{routeJson: routes})

adminAuthorization()
    .then(() => {
        new Vue({
            apiRouter: routeList,
            store,
            router,
            vuetify,
            el: '#app',
            render: h => h(App),
        })
    })
    .catch(() => {
        location.href="/login"
    })

console.log()

async function adminAuthorization() {
    if(Cookies.get('Authorization')){
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
        axios.defaults.headers.common['Authorization'] = `Bearer ${Cookies.get('Authorization')}`
        return axios.get('//api.bikegear.test/v1/user')
    } else {
        return Promise.reject('not found Authorization Cookie')
    }
}
