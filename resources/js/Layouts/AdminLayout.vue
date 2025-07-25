<script setup>
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import Sidebar from '@/Components/Sidebar/Sidebar.vue'
import AdminHeader from '@/Components/HTML/Nav/AdminHeader.vue';

</script>

<template>
    <div class="tw-relative">

        <AlertWrapper></AlertWrapper>
        <AdminHeader />
        <div class="tw-min-h-screen tw-flex tw-flex-row">
            <Sidebar />

            <!-- Page Heading -->
            <div class="tw-w-full">
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
    </div>
</template>