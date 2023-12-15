<script setup>

const props = defineProps({
    position_fixed: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Ups:'
    },
    show_icon: {
        type: Boolean,
        default: true
    }
})

</script>

<template>
    <div :class="position_fixed ? 'fixed bottom-10 shadow-xl left-10 right-10 right-0 z-50 lg:left-1/3 lg:w-1/3 md:left-1/4 md:w-1/2' : ''">
        <div class="bg-blue-100 border-t-4 border-blue-500 rounded-b text-blue-900 px-4 py-3 shadow-md mt-8" role="alert">
            <div class="flex">
                <div class="py-1" v-if="show_icon"><i class="fa-solid fa-face-sad-tear"></i></div>
                <div class="ms-4">
                    <p class="font-bold">{{ title }}</p>
                    <p class="text-sm">
                        <slot></slot>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
