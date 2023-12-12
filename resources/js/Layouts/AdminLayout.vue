<script setup>
import { ref } from 'vue';
import Navbar from '@/Components/Navigation/Navbar.vue'
import { Head } from '@inertiajs/vue3'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';


// const showingNavigationDropdown = ref(false);


</script>

<template>
    <div class="relative">
        <AlertWrapper></AlertWrapper>
        <div class="min-h-screen bg-gray-400">

            <Suspense>
                <Navbar></Navbar>
            </Suspense>
            <!-- Page Heading -->
            <header class="bg-gray-600 shadow" v-if="$slots.header">
                <div class="max-w-8xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
