<template>
    <div
        class="tw-bg-gray-700 tw-p-4 tw-flex tw-flex-row tw-text-gray-100 tw-absolute tw-top-0 tw-left-0 tw-w-full tw-h-full tw-top-0 tw-items-center tw-justify-center tw-opacity-80 tw-rounded tw-z-20">
        <svg class="tw-animate-spin tw--ml-1 tw-mr-3 tw-h-5 tw-w-5 tw-text-white" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 24 24">
            <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="tw-opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        {{ msg }}
    </div>
</template>

<script setup>

defineProps({
    msg: String
})

</script>