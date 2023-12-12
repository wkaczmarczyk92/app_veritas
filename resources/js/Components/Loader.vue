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
    <div class="flex flex-col justify-center items-center mt-10">
        <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-gray-900"></div>
        <div class="mt-4 text-xl">{{ text }}{{ dots }}</div>
    </div>
</template>