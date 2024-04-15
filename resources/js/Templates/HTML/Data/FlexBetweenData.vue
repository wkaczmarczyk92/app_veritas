<template>
    <div class="tw-flex tw-flex-row tw-justify-between hover:tw-bg-gray-200 tw-px-1 tw-rounded">
        <div>{{ label ?? '-' }}</div>
        <div>{{ value ?? '-' }}</div>
    </div>
</template>

<script setup>

defineProps({
    label: {
        type: String,
        required: false
    },
    value: {
        type: String,
        required: false
    }
})

</script>
