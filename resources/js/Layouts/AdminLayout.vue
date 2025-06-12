<script setup>
import Navbar from '@/Components/Navigation/Navbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import Spinner from '@/Composables/Spinner.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue'
import SidebarItem from '@/Components/Sidebar/SidebarItem.vue'
import SidebarHeader from '@/Components/Sidebar/SidebarHeader.vue'
import Sidebar from '@/Components/Sidebar/Sidebar.vue'

const loader = ref(true)

</script>

<template>
    <div class="tw-relative">
        <!-- <Spinner v-if="loader" /> -->

        <AlertWrapper></AlertWrapper>
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
