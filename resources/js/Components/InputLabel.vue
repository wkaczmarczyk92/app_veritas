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
        default: 'tw-text-gray-700'
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
    <label :for="for" class="tw-block tw-font-medium tw-text-sm" :class=" mergeClass() ">
        <span v-if=" value ">{{ value }}</span>
        <span v-else>
            <slot />
        </span>
    </label>
</template>
