<script setup>
defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    bg: {
        type: String,
        default: 'bg-gray-800'
    },
    hover: {
        type: String,
        default: 'hover:bg-gray-700'
    }
})
</script>

<template>
    <button :disabled="disabled"
        :class="`${bg} ${hover}`"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
