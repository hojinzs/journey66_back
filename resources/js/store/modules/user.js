const user = {
    namespaced: true,
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
