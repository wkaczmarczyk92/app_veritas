<script setup>
import Navbar from '@/Pages/CourseModerator/Navigation/Navbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

import TextInput from '@/Components/TextInput.vue';

</script>

<template>
    <div class="tw-relative">
        <AlertWrapper></AlertWrapper>
        <div class="tw-min-h-screen ">

            <Suspense>
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
                <div class="tw-px-4 tw-py-1 tw-mx-auto tw-text-sm tw-text-gray-800 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                    <slot name="header" />
                </div>
                <v-divider :thickness="4" class="border-opacity-75" color="info"></v-divider>
            </header>
            <div class="tw-px-4 tw-mt-5">
                <TextInput type="text" class="tw-mt-1 tw-block rounded-lg tw-bg-gray-100 tw-w-full lg:tw-w-1/3 md:tw-w-1/2" placeholder="wyszukaj..."></TextInput>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
