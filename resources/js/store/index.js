import vue from 'vue'
import Vuex from 'vuex'

/**
 * Load Vuex store file
 */
import user from './user'

vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        user: user
    }
})

export default store
