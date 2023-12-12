<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: { type: String, default: 'Wyślij' },
    icon: { type: String, required: false, default: '' },
    add_class: { type: String, default: '' },
    color: { type: String, default: 'text-white' },
    bg: { type: String, default: 'bg-green-700' },
    hover: { type: String, default: 'hover:bg-green-600' },
    disabled: { type: Boolean, default: false },
    hr: { type: Boolean, default: false },
    subheader: { type: [ Boolean, String ], default: false },
    text: { type:  [Boolean, String ], default: false },
    neon_color: { type: String, default: '' }
});

const neon = {
    
}

// console.log(props.neon_color)

</script>

<template>
    <button 
        :disabled="disabled"
        class="px-4 py-2 rounded-md shadow-xl"
        :class="`${bg} ${hover} ${add_class} ${color} ${neon_color}`">
            <i v-if="icon != ''" :class="`mr-2 ${icon}`"></i>
            {{ value }}
            <div v-if="hr" class="flex justify-center">
                <hr class="my-2 w-1/2 text-center">
            </div>
            <div v-if="subheader" class="mt-2">{{ subheader }}</div>
            <div v-if="text" class="text-center">{{ text }}</div>
    </button>
</template>