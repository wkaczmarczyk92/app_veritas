<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: { type: String, default: 'Wyślij' },
    icon: { type: String, required: false, default: '' },
    add_class: { type: String, default: '' },
    color: { type: String, default: 'tw-text-white' },
    bg: { type: String, default: 'tw-bg-green-700' },
    hover: { type: String, default: 'hover:tw-bg-green-600' },
    disabled: { type: Boolean, default: false },
    hr: { type: Boolean, default: false },
    subheader: { type: [Boolean, String], default: false },
    text: { type: [Boolean, String], default: false },
    neon_color: { type: String, default: '' }
});

const neon = {

}

// console.log(props.neon_color)

</script>

<template>
    <button :disabled="disabled" class="tw-px-4 tw-py-2 tw-rounded-md tw-shadow-xl tw-border-solid"
        :class="`${hover} ${add_class} ${color} ${neon_color} ${bg}`">
        <i v-if="icon != ''" :class="`tw-mr-2 ${icon}`"></i>
        {{ value }}
        <div v-if="hr" class="tw-flex tw-justify-center">
            <hr class="tw-w-1/2 tw-my-2 tw-text-center">
        </div>
        <div v-if="subheader" class="tw-mt-2">{{ subheader }}</div>
        <div v-if="text" class="tw-text-center">{{ text }}</div>
    </button>
</template>
