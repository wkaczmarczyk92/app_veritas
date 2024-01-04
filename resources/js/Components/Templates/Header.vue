<script setup>

import { ref } from 'vue';

const props = defineProps({
    h: {
        type: String,
        default: '2'
    },
    value: {
        type: String,
        required: true
    },
    add_class: {
        type: String
    },
    center: {
        type: Boolean,
        default: false
    },
    icon: {
        type: [String, Boolean],
        default: false
    },
    icon_color: {
        type: String,
        default: 'tw-text-gray-800'
    },
    color: {
        type: String,
        default: 'tw-text-gray-800'
    },
    mb: {
        type: String,
        default: 'tw-mb-3'
    }
})

const header_class = ref({
    h1: 'tw-text-3xl sm:tw-text-4xl tw-font-bold',
    h2: 'tw-text-2xl sm:tw-text-3xl tw-font-bold',
    h3: 'tw-text-xl sm:tw-text-2xl tw-font-bold'
});

</script>

<template>
    <component :is="`h${h}`"
        :class="`${header_class[`h${h}`]} ${center ? 'tw-text-center' : ''} ${add_class} ${color} ${mb}`">
        <i v-if="icon" :class="`${icon} ${icon_color}`"></i>
        {{ value }}
    </component>
</template>
