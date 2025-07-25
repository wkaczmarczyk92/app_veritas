<script setup>

const props = defineProps({
    name: {
        type: [String, null],
        default: null
    },
    value: [String, Number, null]
})

</script>

<template>
    <div class="tw-flex tw-flex-row">
        <div class="tw-w-2/5" v-if="name">
            {{ name }}
        </div>
        <div class="tw-w-2/5" v-if="$slots.name">
            <slot name="name"></slot>
        </div>
        <div class="tw-w-1/5">:</div>
        <div class="tw-w-2/5">
            {{ value }}
        </div>
    </div>
</template>