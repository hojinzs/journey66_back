<template>
    <div>
        <v-card>
            <v-card-title>
                장소 타입 설정
            </v-card-title>
            <v-card-text>
                <place-types />
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import PlaceTypes from "./PlaceTypes";

    export default {
        name: 'types',
        components: {
            PlaceTypes
        },
    }
</script>
