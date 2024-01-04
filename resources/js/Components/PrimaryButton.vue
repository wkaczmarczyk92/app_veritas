<script setup>
defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    bg: {
        type: String,
        default: 'tw-bg-gray-800'
    },
    hover: {
        type: String,
        default: 'hover:tw-bg-gray-700'
    }
})
</script>

<template>
    <button :disabled="disabled" :class="`${bg} ${hover}`"
        class="tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-border tw-border-transparent tw-rounded-md tw-font-semibold tw-text-xs tw-text-white tw-uppercase tw-tracking-widest tw-transition tw-ease-in-out tw-duration-150">
        <slot />
    </button>
</template>
