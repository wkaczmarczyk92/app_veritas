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

setInterval(()=> {
    sec.value--;
    if (sec.value == 0) {
        emit('remove', props.index);
    }
}, 1000)

</script>

<template>
    <div class="w-full rounded-xl text-white shadow-md mt-2 bg-gradient-to-br from-green-700 via-green-600 to-green-900" role="alert">
        <div class="bg-green-900 rounded-tr-xl rounded-tl-xl text-right px-2 py-1 text-md">
            <i class="fa-regular fa-circle-xmark hover:cursor-pointer text-white" @click="$emit('remove', index);"></i>
        </div>
        <div class="px-6 py-4">
            <div class="flex">
                <div class="grow text-center">
                    <slot></slot>
                </div>
            </div>
         </div>
    </div>
</template>