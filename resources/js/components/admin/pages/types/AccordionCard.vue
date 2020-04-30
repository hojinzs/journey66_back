<template>
    <v-card>
        <v-card-title>
            {{ title }}
            <v-btn
                class="sticky-right accordion-control"
                @click="show = !show"
            >
                <v-icon
                    :class="{
                            'toggle-hidden': show
                        }"
                >
                    mdi-chevron-down
                </v-icon>
            </v-btn>
        </v-card-title>
        <div
            class="accordion-menu"
            :class="{
                        'stretch': show,
                        'hidden': !show
                    }"
        >
            <v-card-text>
                <slot name="default">

                </slot>
            </v-card-text>
        </div>
    </v-card>
</template>

<script>
    export default {
        name: "AccordionCard",
        props: {
            title: {
                type: String,
            },
        },
        data() {
            return {
                show: false,
            }
        }
    }
</script>

<style scoped>
    .sticky-right {
        margin-left: auto;
        right: 0;
    }

    .accordion-control {

    }

    .accordion-control .toggle-hidden {
        transform: rotate(180deg)
    }

    .accordion-menu {
        overflow: hidden;
    }

    .accordion-menu.stretch {
        max-height: 5000px;
        transition: max-height 2s;
    }

    .accordion-menu.hidden {
        max-height: 0px;
        transition: max-height 0.5s;
    }
</style>
