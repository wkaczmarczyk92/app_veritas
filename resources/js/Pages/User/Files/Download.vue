<template>
    <Head title="VeritasApp - szukaj ofert" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <FilesToDownload />
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

import FilesToDownload from '@/Pages/User/Files/FilesToDownload.vue';



</script>
