const loader = {
    namespaced: true,
    state: {
        loading: false,
        status: 'pending', // ['pending','loading','success','failed'],
        delay: 500,
    },
    getters: {
        isLoading: state => {
            return state.loading === true
        },
        status: state => {
            return state
        }
    },
    mutations: {
        loading(state){
            state.loading = true
            state.status = 'loading'
        },
        success(state){
            state.loading = false
            state.status = 'success'
        },
        failed(state){
            state.loading = false
            state.status = 'failed'
        },
        pending(state){
            state.loading = false
            state.status = 'pending'
        },
        delay(state, second){
            state.delay = second
        }
    },
    actions: {
        load({commit}){
            commit('loading')
        },
        success({commit, state}){
            commit('success')

            setTimeout(() => {
                commit('pending')
            }, state.delay)
        },
        error({commit, state}){
            commit('failed')

            setTimeout(() => {
                commit('pending')
            }, state.delay)
        }
    }
}

export default loader
