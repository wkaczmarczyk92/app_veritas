<template>
    <a :href="url.includes('/') ? url : route(url)" :target="target"
        class="tw-text-blue-700 hover:tw-text-blue-500 hover:cursor-pointer hover:tw-underline tw-transition tw-duration-400">
        {{ value }}
        <slot name="icon" v-if="$slots.icon">
        </slot>
    </a>
</template>

<script setup>

defineProps({
    url: String,
    value: String,
    target: {
        type: String,
        default: '_self'
    },

})

</script>