import axios from "axios"
import store from "../store/index"

export default (() => {
    axios.interceptors.request.use(
        (config) => {
            store.dispatch('loader/load')
            return config
        },
        (error) => {
            store.dispatch('loader/error')
            return Promise.reject(error);
        });

    axios.interceptors.response.use(
        (response) => {
            store.dispatch('loader/success')
            return response;
        },
        function (error) {
            store.dispatch('loader/error')
            return Promise.reject(error);
        });
})();
