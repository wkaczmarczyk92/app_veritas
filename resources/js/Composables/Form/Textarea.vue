<template>
    <v-textarea variant="outlined" v-model="model_copy" @input="$emit('update:model_value', $event.target.value)"
        type="text" required :error-messages="error ? error : null" :clearable="clearable"
        :hide-details="error ? false : true" @click:clear="$emit('update:model_value', '')"
        :readonly="readonly"></v-textarea>
</template>

<script setup>

import { ref, watch } from 'vue'

const props = defineProps({
    model_value: {
        type: [String, null],
        required: true
    },
    error: {
        type: [String, null, undefined],
        required: true
    },
    clearable: {
        type: Boolean,
        default: true
    },
    readonly: {
        type: Boolean,
        default: false
    }
})

const model_copy = ref(props.model_value)

defineEmits(['update:model_value'])

watch(() => props.model_value, (new_value) => {
    model_copy.value = new_value;
});

</script>
