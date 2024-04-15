<template>
    <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-2">
        <div class="tw-flex tw-flex-row tw-gap-2 tw-justify-between tw-items-center">
            <div class="tw-flex tw-flex-row tw-gap-1 tw-items-center">
                <slot name="icon"></slot>
                {{ title }}
            </div>
            <div>
                <slot name="value">{{ value }}</slot>
            </div>
        </div>
    </div>
</template>

<script setup>

defineProps({
    title: String,
    value: String
})

</script>
