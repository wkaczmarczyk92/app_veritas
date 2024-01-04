<script setup>

import { ref } from 'vue'

const props = defineProps({
    modelValue: {
        type: Boolean
    },
    position_fixed: {
        type: Boolean,
        default: false
    },
    show_counter: {
        type: Boolean,
        default: true
    },
    show_close_button: {
        type: Boolean,
        default: true
    }
});

console.log(props.show_close_button);

const sec = ref(5);

if (props.show_counter) {
    setInterval(() => {
        sec.value--;
    }, 1000)
}

defineEmits([
    'update:modelValue'
]);


</script>

<template>
    <div
        :class="position_fixed ? 'tw-fixed tw-bottom-10 tw-shadow-xl tw-left-10 tw-right-10 tw-right-0 tw-z-50 lg:tw-left-1/3 lg:tw-w-1/3 md:tw-left-1/4 md:tw-w-1/2' : ''">
        <div class="tw-bg-red-100 tw-border-t-4 tw-border-red-500 tw-rounded-b tw-text-red-900 tw-px-4 tw-py-3 tw-shadow-md tw-mt-8"
            role="alert">
            <div class="tw-flex">
                <div class="tw-py-1"><i class="fa-solid fa-face-sad-tear"></i></div>
                <div class="tw-ms-4 tw-w-full">
                    <div class="tw-flex tw-justify-between tw-mb-3">
                        <p class="tw-font-bold">Ups:</p>
                        <i v-if="show_close_button"
                            class="fa-regular fa-circle-xmark hover:tw-cursor-pointer tw-text-red-600 hover:tw-text-red-800"
                            @click="$emit('update:modelValue', false)"></i>
                    </div>
                    <div class="flex justify-between">
                        <p class="tw-text-sm">
                            <slot></slot>
                        </p>
                        <div v-if="show_counter" class="tw-text-sm">{{ `...${sec}` }}</div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
