<template>
    <div class="tw-flex tw-flex-row tw-gap-2 tw-px-1 tw-items-center">
        <div v-if="icon">
            <i :class="icon"></i>
        </div>
        <div>{{ value }}</div>
    </div>
</template>

<script setup>

defineProps({
    icon: {
        type: String,
        required: false
    },
    value: {
        type: String,
        required: true
    }
})

</script>
