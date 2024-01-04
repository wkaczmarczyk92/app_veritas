<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
    id: {
        type: String,
        defualt: ''
    }
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input :id="id" type="checkbox" :value="value" v-model="proxyChecked"
        class="tw-rounded tw-border-gray-400 tw-text-gray-800 tw-shadow-sm focus:tw-ring-0 focus:tw-outline-0 tw-mr-2" />
</template>
