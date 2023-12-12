<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
</script>

<template>
    <AlertWrapper></AlertWrapper>
    <div class="min-h-screen flex flex-col sm:justify-center items-center sm:items-start sm:pl-36 px-4 pt-6 sm:pt-0 bg-gray-800 login-bg">
        

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-100 shadow-xl overflow-hidden sm:rounded-lg"
        >
            <div class="flex flex-row justify-center mb-10 mt-4">
                <Link href="/">
                    <ApplicationLogo class="w-auto h-24 fill-current text-gray-500" />
                </Link>
            </div>
            <slot />
        </div>
    </div>
</template>
