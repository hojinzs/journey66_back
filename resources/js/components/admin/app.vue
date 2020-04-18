<template>
    <v-app>
        <v-navigation-drawer app>
            <!-- -->
            <template v-slot:prepend>
                <v-list>
                    <v-list-item>
                        <v-list-item-icon>

                        </v-list-item-icon>
                        <v-list-item-content>
                            Journey66 ADMIN
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item
                        :class="'justify-end'"
                    >
                        <form method="GET" action="/logout">
                            <v-btn class="float-md-right" color="light-blue lighten-3" type="submmit">로그아웃</v-btn>
<!--                            <button type="submit">로그아웃</button>-->
                        </form>
                    </v-list-item>
                </v-list>
                <v-divider></v-divider>
            </template>

            <v-list-item-group
                :mandatory="true"
                v-model="model"
            >
                <v-list-item
                    v-for="item in adminMenu"
                    :key="item.name"
                    @click="routeChange(item.routeName)"
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title>{{ item.label }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </v-list-item-group>

        </v-navigation-drawer>

        <v-app-bar app>
            <!-- -->
            <h2>{{ $route.meta.label }}</h2>
        </v-app-bar>

        <!-- Sizes your content based upon application components -->
        <v-content>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <!-- If using vue-router -->
                <router-view></router-view>
            </v-container>
        </v-content>

        <v-footer app>
            <!-- -->
        </v-footer>
    </v-app>
</template>

<script>
    import adminMenu from '../../plugins/admin-menu.js'

    export default {
        name: 'app',
        data(){
            return {
                adminMenu,
                model: undefined
            }
        },
        computed: {
            currentRouteGroupNumber(){
                let current = this.adminMenu.findIndex( menu => {
                    return menu.groupName === this.$route.meta.group
                })
                if(current === -1 ){
                    return undefined
                }
                return current
            }
        },
        created() {
            this.model = this.currentRouteGroupNumber
            console.log(this.$store.state.user)
        },
        methods: {
            routeChange(routeName){
                this.$router.push({name: routeName})
            }
        },
        mounted() {

        },
        watch: {
            $route(){
                this.model = this.currentRouteGroupNumber
            },
        }
    }
</script>
