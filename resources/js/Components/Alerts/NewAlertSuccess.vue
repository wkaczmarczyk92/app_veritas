<script setup>

import { ref } from 'vue'

const props = defineProps({
    index: {
        type: Number,
        required: true
    }
});

const emit = defineEmits([
    'remove'
]);

const sec = ref(5);

setInterval(() => {
    sec.value--;
    if (sec.value == 0) {
        emit('remove', props.index);
    }
}, 1000)

</script>

<template>
    <v-alert closable type="success" @click:close="$emit('remove', index);">
        <template v-slot:text>
            <slot></slot>
        </template>
    </v-alert>
</template>
