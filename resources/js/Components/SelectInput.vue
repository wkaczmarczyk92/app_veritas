<script setup>
defineProps({
    modelValue: {
        type: [String, Number, null],
        required: true,
        default: ''
    },
    options: {
        type: [Array, Object],
        required: true
    },
    name_string: {
        type: String,
        required: true,
        default: 'name'
    },
    class: {
        type: String,
        default: ''
    }
});

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="tw-relative">
        <select :class="class" @change="$emit('update:modelValue', $event.target.value)" :value="modelValue"
            class="tw-block tw-appearance-none tw-w-full tw-bg-white tw-border tw-border-gray-300 tw-text-gray-700 tw-py-2 tw-px-3 tw-pr-8 tw-rounded tw-leading-tight focus:tw-outline-none focus:tw-bg-white focus:tw-border-gray-500">
            <option value="">...</option>
            <option v-for="option in options" :value="option.id">{{ option[name_string] }}</option>
        </select>
    </div>
</template>
