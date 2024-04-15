<template>
    <a v-if="url" :href="url">
        <v-chip variant="tonal" :size="options.size"
            class="!tw-text-purple-600 hover:tw-cursor-pointer hover:!tw-text-purple-800">
            {{ vademecum_name }}
        </v-chip>
    </a>
    <v-chip v-else variant="tonal" color="#db2777" size="small">
        {{ vademecum_name }}
    </v-chip>
</template>

<script setup>

import { ref } from 'vue'

defineProps({
    vademecum_name: String,
    url: {
        type: [String, null],
        default: null
    }
})

const options = ref({
    size: 'small',
})

</script>
