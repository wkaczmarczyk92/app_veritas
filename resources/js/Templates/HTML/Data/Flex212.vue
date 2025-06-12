<template>
    <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
        <div class="tw-w-2/5">
            <slot name="title" v-if="$slots.title"></slot>
            <span v-else>{{ title }}</span>
        </div>
        <div class="tw-w-[20px]">:</div>
        <div class="tw-w-full">
            <slot name="value" v-if="$slots.value"></slot>
            <span v-else>{{ value }}</span>
        </div>
    </div>
</template>

<script setup>

defineProps({
    title: {
        type: [String, null],
        default: null
    },
    value: {
        type: [String, Number, null],
        default: null
    },

})

</script>