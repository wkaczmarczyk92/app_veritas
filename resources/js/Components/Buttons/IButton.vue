<script setup>

import { ref } from 'vue';

defineProps({
    icon: {
        type: String,
        required: true
    },
    size: {
        type: String,
        default: 'md'
    },
    bg: {
        type: String,
        default: 'bg-red-600'
    },
    color: {
        type: String,
        default: 'text-white'
    }

});

const size_arr = ref({
    sm: 'px',
    md: 'px-10 py-2',
    lg: '',
    xl: ''
})

</script>

<template>
    <button 
        :class="`${size_arr[size]} ${bg} ${color} shadow-2xl rounded-xl`">
            <i :class="icon" class="text-4xl"></i>
    </button>
</template>