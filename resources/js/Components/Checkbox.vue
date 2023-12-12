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
    <input
        :id="id"
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-400 text-gray-800 shadow-sm focus:ring-0 focus:outline-0 mr-2"
    />
</template>
