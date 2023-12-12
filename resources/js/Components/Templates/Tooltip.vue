<template>
    <div :class="mergeClass()" :style="`${style}`">
        <slot></slot>
    </div>
</template>

<script setup>

import { toRef } from 'vue';

const props = defineProps({
    class: {
        type: String,
        default: ''
    },
    left: {
        type: Boolean,
        default: true
    },
    style: {
        type: String,
        default: 'background-color: #202060'
    },
    top: {
        type: Number,
        default: 10
    },
    translateX: {
        type: Boolean,
        default: false
    }
})

const isLeft = () => {
    return props.left ? 'position-left' : 'position-right'
}

const mergeClass = () => {
    return `tooltip absolute z-40 ${props.class} ${isLeft()}` + (props.translateX ? ' t-x' : '')
}

const top = toRef(props.top);

</script>

<style>

.tooltip {
    display: block ruby;
    top: calc(-100% - v-bind(top + 'px'));
    /* background-color: #202060; */
    color: white;
    padding: 5px 10px;
    border-radius: 10px;
}

.position-left {
    left: 0;
}

.position-right {
    right: 0;
}

.t-x {
    transform: translateX(-50%);
}

</style>