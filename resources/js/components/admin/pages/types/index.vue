<template>
    <div>
        <div class="py-5">
            <accordion-card
                title="장소 타입 설정"
            >
                <place-types />
            </accordion-card>
        </div>
        <div class="py-5">
            <accordion-card
                title="태그 타입 설정"
            >
                <tag-types />
            </accordion-card>
        </div>
    </div>
</template>

<script>
    import PlaceTypes from "./PlaceTypes";
    import TagTypes from "./TagTypes";
    import AccordionCard from "./AccordionCard";

    export default {
        name: 'types',
        components: {
            AccordionCard,
            PlaceTypes,
            TagTypes
        },
        data() {
            return {
                showPlaceTypes: false,
            }
        }
    }
</script>
