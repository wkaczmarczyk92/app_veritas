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
  <textarea :rows="rows" @input="$emit('update:modelValue', $event.target.value)" :value="modelValue" :class="class" class="block w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
</template>