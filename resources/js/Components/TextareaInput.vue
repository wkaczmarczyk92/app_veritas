<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true,
        default: ''
    },
    class: {
        type: String,
        default: ''
    },
    rows: {
        type: Number,
        default: 10
    }
});

defineEmits(['update:modelValue']);
</script>

<template>
    <textarea :rows="rows" @input="$emit('update:modelValue', $event.target.value)" :value="modelValue" :class="class"
        class="tw-block tw-w-full tw-bg-white tw-border tw-border-gray-300 tw-text-gray-700 tw-py-2 tw-px-3 tw-rounded-lg tw-resize-none focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 tw-border-solid"></textarea>
</template>
