<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
    class: {
        type: String,
        default: ''
    }
});
</script>

<template>
    <Link :href="href"
        class="tw-block tw-w-full tw-px-4 tw-py-2 tw-text-left tw-text-sm tw-leading-5 tw-text-gray-700 hover:tw-bg-gray-100 focus:tw-outline-none focus:tw-bg-gray-100 tw-transition tw-duration-150 tw-ease-in-out"
        :class="class">
    <slot />
    </Link>
</template>
