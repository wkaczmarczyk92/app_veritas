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
    <div class="absolute loader-wrapper">
        <div class="flex flex-col justify-center items-center">
            <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-gray-300"></div>
            <div class="mt-4 text-xl text-gray-300">{{ text }}{{ dots }}</div>
        </div>
    </div>
</template>

<style scoped>

.loader-wrapper {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
}

.loader-wrapper > div {
    /* position: absolute;
    top: 50%; */
    height: 100%;
}

</style>