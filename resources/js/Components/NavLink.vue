<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'tw-relative tw-inline-flex tw-items-center tw-px-1 tw-pt-1 tw-border-b-2 active-navbar-item tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-200 focus:tw-outline-none focus:tw-border-indigo-700 tw-transition tw-duration-150 tw-ease-in-out'
        : 'tw-relative tw-inline-flex tw-items-center tw-px-1 tw-pt-1 tw-border-b-2 tw-border-transparent tw-text-sm tw-font-medium tw-leading-5 tw-text-gray-300 hover:tw-text-gray-400 hover:tw-border-gray-300 focus:tw-outline-none focus:tw-text-gray-700 focus:tw-border-gray-300 tw-transition tw-duration-150 tw-ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes">
    <slot />
    </Link>
</template>
