<template>
    <v-app>
        <v-navigation-drawer app>
            <!-- -->

            네비게이션 그리기
        </v-navigation-drawer>

        <v-app-bar app>
            <!-- -->

            헤더 앱 바
        </v-app-bar>

        <!-- Sizes your content based upon application components -->
        <v-content>
            <!-- Provides the application the proper gutter -->
            <v-container fluid>
<!--                콘텐츠 콘테이너-->
                <!-- If using vue-router -->
                <router-view></router-view>
            </v-container>
        </v-content>

        <v-footer app>
            <!-- -->
            푸터입니다
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        name: 'app',
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
