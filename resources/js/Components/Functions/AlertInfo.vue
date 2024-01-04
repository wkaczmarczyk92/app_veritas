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
    <div
        :class="position_fixed ? 'tw-fixed tw-bottom-10 tw-shadow-xl tw-left-10 tw-right-0 tw-z-50 lg:tw-left-1/3 lg:tw-w-1/3 md:tw-left-1/4 md:tw-w-1/2' : ''">
        <div class="tw-px-4 tw-py-3 tw-text-blue-900 tw-bg-blue-100 tw-border-t-4 tw-border-blue-500 tw-rounded-b tw-shadow-md"
            role="alert">
            <div class="tw-flex">
                <div class="tw-py-1" v-if="show_icon"><i class="fa-solid fa-face-sad-tear"></i></div>
                <div class="tw-ms-4">
                    <p class="tw-font-bold">{{ title }}</p>
                    <p class="tw-text-sm">
                        <slot></slot>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
