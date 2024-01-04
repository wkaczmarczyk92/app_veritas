<script setup>

import { ref } from 'vue';

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    value: {
        type: String,
        default: 'Wyślij'
    },
    icon: {
        type: [String, Boolean],
        default: false
    },
    btn_off: {
        type: Boolean,
        default: false
    }
})

const dots = ref('.');

</script>

<template>
    <button :disabled="disabled"
        class="btn-success tw-inline-flex tw-items-center tw-px-4 tw-py-2 tw-bg-transparent disabled:tw-bg-green-700 disabled:tw-text-gray-200 disabled:tw-pointer-events-none tw-mr-1 tw-text-green-700 tw-border-solid tw-border tw-border-green-700 tw-rounded-md tw-font-semibold tw-text-xs tw-uppercase tw-tracking-widest hover:tw-text-white hover:tw-bg-green-700 focus:tw-outline-none tw-transition tw-ease-in-out tw-duration-150">
        <span v-if="icon" v-html="icon"></span>
        <div v-if="!disabled">
            {{ value }}
        </div>
        <div v-else-if="disabled && !btn_off">Przetwarzanie...</div>
        <div v-else>{{ value }}</div>

    </button>
</template>
