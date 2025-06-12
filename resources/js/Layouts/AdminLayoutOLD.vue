<script setup>
import Navbar from '@/Components/Navigation/Navbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import Spinner from '@/Composables/Spinner.vue';

import { ref } from 'vue'

const loader = ref(true)

</script>

<template>
    <div class="tw-relative">
        <Spinner v-if="loader" />

        <AlertWrapper></AlertWrapper>
        <div class="tw-min-h-screen ">

            <Suspense @resolve="loader = false">
                <template #default>
                    <Navbar #default></Navbar>
                </template>
                <template #fallback>
                    <nav class="tw-bg-gray-800 tw-border-b tw-border-gray-100 tw-min-h-[100px]">
                        <div class="tw-p-4 tw-mx-auto tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                        </div>
                    </nav>
                </template>
            </Suspense>
            <!-- Page Heading -->
            <header class="" v-if="$slots.header">
                <div
                    class="tw-px-4 tw-py-1 tw-mx-auto tw-text-sm tw-text-gray-800 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8 tw-flex tw-flex-row">
                    <slot name="header" />
                </div>
                <v-divider :thickness="4" class="border-opacity-75" color="info"></v-divider>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
