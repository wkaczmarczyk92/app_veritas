<template>
    <div
        class="tw-bg-gray-200 tw-h-full tw-flex tw-text-white tw-p-1 tw-rounded-lg tw-flex-col tw-justify-center !tw-text-gray-600 tw-transition tw-duration-300 hover:tw-scale-110 hover:tw-shadow-xl hover:tw-shadow-gray-600 hover:tw-cursor-pointer">
        <slot></slot>
    </div>
</template>

<script setup>

const props = defineProps({
    title: {
        type: String,
        default: 'brak nazwy pliku'
    }
})

</script>