<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <nav class="tw-bg-gray-800 tw-border-b tw-border-gray-500">
        <!-- Primary Navigation Menu -->
        <div class="tw-max-w-7xl tw-mx-auto tw-p-4 sm:tw-px-6 lg:tw-px-8">
            <div class="tw-flex tw-justify-between tw-h-16">
                <div class="tw-flex">
                    <!-- Logo -->
                    <div class="tw-shrink-0 tw-flex tw-items-center">
                        <Link :href="route('dashboard')">
                        <ApplicationLogo class="tw-block tw-h-16 tw-w-auto tw-fill-current" />
                        </Link>
                    </div>

                    <div class="tw-hidden tw-space-x-8 sm:-tw-my-px sm:tw-ml-10 sm:tw-flex">
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
