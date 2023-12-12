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
})

console.log(props)

defineEmits([
    'update:modelValue'
]);

const sec = ref(5);

if (props.show_counter) {
    setInterval(()=> {
        sec.value--;
    }, 1000)
}


</script>

<template>
    <div :class="position_fixed ? 'fixed bottom-10 shadow-xl left-10 right-10 right-0 z-50 lg:left-1/3 lg:w-1/3 md:left-1/4 md:w-1/2' : ''">
        <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md mt-8" role="alert">
            <!-- <div class="w-full text-right" v-if="show_close_button">
                <i class="fa-regular fa-circle-xmark hover:cursor-pointer text-red-600 hover:text-red-800" @click="$emit('update:modelValue', false)"></i>
            </div> -->
            <div class="flex">
                <div class="py-1"><i class="fa-solid fa-thumbs-up"></i></div>
                <div class="ms-4 w-full">
                    <div class="flex justify-between mb-3">
                        <p class="font-bold">Sukces:</p>
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