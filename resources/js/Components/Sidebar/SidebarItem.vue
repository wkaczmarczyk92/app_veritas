<script setup>

defineProps({
    href: {
        type: String,
        required: false,
        default: '#'
    },
    active: {
        type: Boolean,
        required: false,
        default: false
    },
    subItem: {
        type: Boolean,
        default: false
    }
})

</script>

<template>
    <a :href="href"
        class="tw-flex tw-items-center tw-gap-3 tw-px-3 tw-py-2 tw-rounded-md hover:tw-bg-gray-800 tw-cursor-pointer"
        :class="(active ? 'tw-border tw-border-gray-200' : '')">
        <div class="" v-if="$slots.icon">
            <slot name="icon"></slot>
        </div>
        <div class="tw-flex tw-flex-row tw-justify-between tw-w-full tw-items-center">
            <slot name="title"></slot>
            <div class="tw-flex tw-flex-row">
                <slot name="badges"></slot>
            </div>
        </div>
    </a>
</template>