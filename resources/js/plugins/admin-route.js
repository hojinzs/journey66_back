import Vue from 'vue'
import VueRouter from 'vue-router'

/**
 * import Page Components
 */
import home from '../components/admin/pages/home'
import users from '../components/admin/pages/users'
import places from '../components/admin/pages/places'
import placeDetail from '../components/admin/pages/places-detail'
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
            meta: {
                label: '홈',
                group: 'home'
            }
        },
        {
            path: '/users',
            name: 'Users',
            component: users,
            meta: {
                label: '회원',
                group: 'users'
            }
        },
        {
            path: '/places',
            name: 'Places',
            component: places,
            meta: {
                label: '장소',
                group: 'places'
            }
        },
        {
            path: '/places/:place_id',
            name: 'Places.show',
            component: placeDetail,
            meta: {
                label: '장소 상세 정보',
                group: 'places'
            }
        },
        {
            path: '/tags',
            name: 'Tags',
            component: tags,
            meta: {
                label: '태그',
                group: 'tags'
            }
        },
        {
            path: '*',
            name: '404',
            component: pageNotFound,
        }
    ]
})

export default router
