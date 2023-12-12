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
    <div class="relative">
        <select :class="class" @change="$emit('update:modelValue', $event.target.value)" :value="modelValue" class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
            <option value="">...</option>
            <option v-for="option in options" :value="option.id">{{ option[name_string] }}</option>
        </select>
    </div>
</template>