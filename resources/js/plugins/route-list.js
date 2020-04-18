/**
 * Route Maker from Laravel route:list
 * Reference: https://ideas.hexbridge.com/how-to-use-laravel-routes-in-javascript-4d9c484a0d97
 */

const routeList = {
    install: function(Vue, options){

        const routeJson = options.routeJson;
        const baseUrl = process.env.MIX_ADMIN_API_HOST;

        Vue.prototype.$routeList = function(){
            let args = Array.prototype.slice.call(arguments);
            let name = args.shift();

            if (typeof routeJson[name] === 'undefined') {
                console.error('Unknown route ', name);
            } else {
                return baseUrl + '/' + routeJson[name]
                    .split('/')
                    .map(s => s[0] == '{' ? args.shift() : s)
                    .join('/');
            }
        }
    }
}

export default routeList
