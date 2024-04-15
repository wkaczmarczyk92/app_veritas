<template>
    <a :href="url" class="tw-text-blue-700 hover:tw-text-blue-900 hover:tw-underline">
        <slot></slot>
    </a>
</template>

<script setup>

defineProps({
    url: {
        type: String,
        required: true
    },
})

</script>