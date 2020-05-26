import Vue from 'vue'
import VueRouter from 'vue-router'

/**
 * import Page Components
 */
import home from '../components/admin/pages/home'
import users from '../components/admin/pages/users/index'
import userDetail from '../components/admin/pages/users/detail'
import places from '../components/admin/pages/places/index'
import placeCreate from '../components/admin/pages/places/create'
import placeDetail from '../components/admin/pages/places/detail'
import tags from '../components/admin/pages/tags/index'
import tagDetail from '../components/admin/pages/tags/detail'
import tagCreate from '../components/admin/pages/tags/create'
import types from '../components/admin/pages/types/index'
import options from '../components/admin/pages/options/index'
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
            path: '/users/:user_id',
            name: 'Users.Show',
            component: userDetail,
            meta: {
                label: '회원 정보',
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
            path: '/places/create',
            name: 'Places.create',
            component: placeCreate,
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
            path: '/tags/create',
            name: 'Tags.Create',
            component: tagCreate,
            meta: {
                label: '태그 등록',
                group: 'tags'
            }
        },
        {
            path: '/tags/:tag_id',
            name: 'Tags.Show',
            component: tagDetail,
            meta: {
                label: '태그 상세 정보',
                group: 'tags'
            }
        },
        {
            path: '/types',
            name: 'Types',
            component: types,
            meta: {
                label: '타입 설정',
                group: 'types'
            }
        },
        {
            path: '/options',
            name: 'Options',
            component: options,
            meta: {
                label: '선택값 설정',
                group: 'options'
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
