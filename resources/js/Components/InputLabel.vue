<script setup>
const props = defineProps({
    value: {
        type: String,
    },
    class: {
        type: String,
        default: ''
    },
    text_color: {
        type: String,
        default: 'text-gray-700'
    },
    for: {
        type: String,
        defualt: ''
    }
});

const mergeClass = () => {
    return props.class + ' ' + props.text_color;
}

</script>

<template>
    <label :for="for" class="block font-medium text-sm" :class="mergeClass()">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
