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
    <div class="tw-w-full tw-rounded tw-text-red-500 tw-shadow-md tw-mt-2 tw-bg-red-100 tw-border tw-border-red-300 tw-relative"
        role="alert">
        <i class="fa-regular fa-circle-xmark hover:tw-cursor-pointer tw-text-red-500 tw-absolute tw-right-2 tw-top-2"
            @click="$emit('remove', index);"></i>
        <div class="tw-px-6 tw-py-6">
            <div class="tw-flex">
                <div class="tw-grow tw-text-center">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>
