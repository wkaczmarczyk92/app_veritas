<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: {
        type: [String, Number, null],
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 tw-border-solid focus:ring-indigo-500 rounded-md shadow-sm disabled:bg-gray-200 disabled:cursor-not-allowed"
        :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" :disabled="disabled" />
</template>
