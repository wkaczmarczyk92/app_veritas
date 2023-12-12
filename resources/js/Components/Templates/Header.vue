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
        default: 'text-gray-800'
    },
    color: {
        type: String,
        default: 'text-gray-800'
    },
    mb: {
        type: String,
        default: 'mb-3'
    }
})

const header_class = ref({
    h1: 'text-3xl sm:text-4xl font-bold',
    h2: 'text-2xl sm:text-3xl font-bold',
    h3: 'text-xl sm:text-2xl font-bold'
});

</script>

<template>
    <component 
        :is="`h${h}`" 
        :class="`${header_class[`h${h}`]} ${center ? 'text-center' : ''} ${add_class} ${color} ${mb}`">
            <i v-if="icon" :class="`${icon} ${icon_color}`"></i>
            {{ value }}
    </component>
</template>