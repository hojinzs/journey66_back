import vue from 'vue'
import Vuex from 'vuex'

/**
 * Load Vuex store file
 */
import user from './modules/user'
import loader from './modules/loader'

vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        user: user,
        loader: loader,
    }
})

export default store
