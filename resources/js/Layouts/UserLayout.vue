<script setup>
import UserNavbar from '@/Components/Navigation/UserNavbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

</script>

<template>
    <div class="relative">
        <AlertWrapper></AlertWrapper>
        <div class="min-h-screen bg-gradient-to-br from-gray-400 to-gray-700">
            <UserNavbar></UserNavbar>
            <main class="background relative">
                <slot />
            </main>
        </div>
    </div>
</template>
