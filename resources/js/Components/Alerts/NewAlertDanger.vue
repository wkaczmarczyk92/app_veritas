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
    <div class="tw-w-full tw-rounded-xl tw-text-white tw-shadow-md tw-mt-2 tw-bg-gradient-to-br tw-from-red-700 tw-via-red-600 tw-to-red-900"
        role="alert">
        <div class="tw-bg-red-900 tw-rounded-tr-xl tw-rounded-tl-xl tw-text-right tw-px-2 tw-py-1 tw-text-md">
            <i class="fa-regular fa-circle-xmark hover:tw-cursor-pointer tw-text-white"
                @click="$emit('remove', index);"></i>
        </div>
        <div class="tw-px-6 tw-py-4">
            <div class="tw-flex">
                <div class="tw-grow tw-text-center">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>
