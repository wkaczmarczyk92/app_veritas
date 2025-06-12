<script setup>

import { ref } from 'vue';

defineProps({
    text: {
        type: String,
        default: 'Ładowanie danych'
    }
})

const dots = ref('.')

setInterval(() => {
    dots.value = dots.value.length == 3 ? '.' : dots.value + '.';
}, 500)

</script>

<template>
    <div class="tw-flex tw-flex-col tw-justify-center tw-items-center tw-mt-10">
        <div class="tw-animate-spin tw-rounded-full tw-h-20 tw-w-20 tw-border-t-4 tw-border-b-4 tw-border-gray-900">
        </div>
        <div class="tw-mt-4 tw-text-xl">{{ text }}{{ dots }}</div>
    </div>
</template>
