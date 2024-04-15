<template>
    <div class="tw-flex tw-flex-row tw-gap-2 tw-px-1 tw-items-center">
        <div v-if="icon">
            <i :class="icon"></i>
        </div>
        <div>
            <a :href="url" target="_blank"
                class="tw-text-blue-600 hover:tw-text-blue-500 hover:tw-underline tw-transition tw-duration-200">
                {{ value }}
            </a>
        </div>
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
    },
    url: {
        type: String,
        required: true
    }
})

</script>
