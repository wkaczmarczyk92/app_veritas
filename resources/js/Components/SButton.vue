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
    <button
        :disabled="disabled"
        class="btn-success inline-flex items-center px-4 py-2 bg-transparent disabled:bg-green-700 disabled:text-gray-200 disabled:pointer-events-none mr-1 text-green-700 border border-green-700 rounded-md font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-green-700 focus:outline-none transition ease-in-out duration-150">
        <span v-if="icon" v-html="icon"></span>
        <div v-if="!disabled">
             {{ value }}
        </div> 
        <div v-else-if="disabled && !btn_off">Przetwarzanie...</div>
        <div v-else>{{ value }}</div>
        
    </button>
</template>
