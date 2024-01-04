<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
</script>

<template>
    <AlertWrapper></AlertWrapper>
    <div
        class="tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center sm:tw-items-start sm:tw-pl-36 tw-px-4 tw-pt-6 sm:tw-pt-0 tw-bg-gray-800 login-bg">


        <div
            class="tw-w-full sm:tw-max-w-md tw-mt-6 tw-px-6 tw-py-4 tw-bg-gray-100 tw-shadow-xl tw-overflow-hidden sm:tw-rounded-lg">
            <div class="tw-flex tw-flex-row tw-justify-center tw-mb-10 tw-mt-4">
                <Link href="/">
                <ApplicationLogo class="tw-w-auto tw-h-24 tw-fill-current tw-text-gray-500" />
                </Link>
            </div>
            <slot />
        </div>
    </div>
</template>
