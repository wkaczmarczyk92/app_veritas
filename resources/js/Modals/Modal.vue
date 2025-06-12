<script setup>


</script>

<template>
    <div id="modal"
        class="tw-fixed tw-inset-0 tw-overflow-y-auto tw-px-4 tw-py-6 sm:tw-px-0 tw-z-50 tw-bg-black tw-bg-opacity-50">
        <div class="tw-bg-white tw-rounded-lg tw-p-6 tw-w-full md:tw-w-4/5 lg:tw-w-1/2 tw-mx-auto">
            <slot></slot>
        </div>
    </div>
</template>
