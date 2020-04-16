import axios from 'axios'
import Cookies from 'js-cookie'

const user = {
    namespace: true,
    state: {
        status: 'pending',
        user: null,
    },
    mutations: {
        login(state, data){
            state.user = data
        }
    },
}

export default user
