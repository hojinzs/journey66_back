import Vue from 'vue'
import VueRouter from 'vue-router'

/**
 * import Page Components
 */
import home from '../components/admin/pages/home'
import users from '../components/admin/pages/users'
import places from '../components/admin/pages/places'
import tags from '../components/admin/pages/tags'
import pageNotFound from '../components/admin/pages/404'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Home',
            component: home,
        },
        {
            path: '/users',
            name: 'Users',
            component: users,
        },
        {
            path: '/places',
            name: 'Places',
            component: places,
        },
        {
            path: '/tags',
            name: 'Tags',
            component: tags
        },
        {
            path: '*',
            name: '404',
            component: pageNotFound,
        }
    ]
})

export default router
