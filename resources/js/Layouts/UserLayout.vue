<script setup>
import UserNavbar from '@/Components/Navigation/UserNavbar.vue'
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';

import FloatingUserMenu from '@/Components/Templates/FloatingUserMenu.vue';
import Modals from '@/Modals/Modals.vue';

</script>

<template>
    <div class="tw-relative">
        <AlertWrapper></AlertWrapper>
        <div class="tw-min-h-screen tw-bg-gradient-to-br tw-from-gray-400 tw-to-gray-700">
            <UserNavbar></UserNavbar>
            <main class="tw-background tw-relative">
                <slot />
            </main>
            <Suspense>
                <FloatingUserMenu></FloatingUserMenu>
            </Suspense>

            <Suspense>
                <Modals></Modals>
            </Suspense>
        </div>
    </div>
</template>
