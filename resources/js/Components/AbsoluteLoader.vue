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
    <div class="tw-absolute loader-wrapper">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
            <div class="tw-animate-spin tw-rounded-full tw-h-20 tw-w-20 tw-border-t-4 tw-border-b-4 tw-border-gray-300">
            </div>
            <div class="tw-mt-4 tw-text-xl tw-text-gray-300">{{ text }}{{ dots }}</div>
        </div>
    </div>
</template>

<style scoped>
.loader-wrapper {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
}

.loader-wrapper>div {
    /* position: absolute;
    top: 50%; */
    height: 100%;
}
</style>
