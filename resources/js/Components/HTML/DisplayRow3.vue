<script setup>

const props = defineProps({
    name: String,
    value: [String, Number, null]
})

</script>

<template>
    <div class="tw-flex tw-flex-row">
        <div class="tw-w-2/5">
            {{ name }}:
        </div>
        <div class="tw-w-1/5">:</div>
        <div class="tw-w-2/5">
            {{ value }}
        </div>
    </div>
</template>