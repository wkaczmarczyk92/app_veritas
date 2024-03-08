<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

import PasswordUpdateForm from '@/Components/Password/PasswordUpdateForm.vue';

const props = defineProps({

});

</script>

<template>
    <Head title="VeritasApp - ważne informacje" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-3xl sm:tw-px-6 lg:tw-px-8 ">
                <PasswordUpdateForm />
            </div>
        </div>
    </UserLayout>
</template>
