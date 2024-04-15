<template>
    <a v-if="url" :href="url">
        <v-chip variant="tonal" :size="options.size"
            class="!tw-text-sky-600 hover:tw-cursor-pointer hover:!tw-text-sky-800">
            {{ lesson_name }}
        </v-chip>
    </a>
    <v-chip v-else variant="tonal" :color="options.color" :size="options.size">
        {{ lesson_name }}
    </v-chip>
</template>

<script setup>

import { ref } from 'vue'

defineProps({
    lesson_name: String,
    url: {
        type: [String, null],
        default: null
    }
})

const options = ref({
    size: 'small',
})

</script>
