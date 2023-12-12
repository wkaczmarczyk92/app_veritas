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
    setInterval(()=> {
        sec.value--;
    }, 1000)
}

defineEmits([
    'update:modelValue'
]);


</script>

<template>
    <div :class="position_fixed ? 'fixed bottom-10 shadow-xl left-10 right-10 right-0 z-50 lg:left-1/3 lg:w-1/3 md:left-1/4 md:w-1/2' : ''">
        <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mt-8" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fa-solid fa-face-sad-tear"></i></div>
                <div class="ms-4 w-full">
                    <div class="flex justify-between mb-3">
                        <p class="font-bold">Ups:</p>
                        <i v-if="show_close_button" class="fa-regular fa-circle-xmark hover:cursor-pointer text-red-600 hover:text-red-800" @click="$emit('update:modelValue', false)"></i>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm">
                            <slot></slot>
                        </p>
                        <div v-if="show_counter" class="text-sm">{{ `...${sec}` }}</div>

                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>
