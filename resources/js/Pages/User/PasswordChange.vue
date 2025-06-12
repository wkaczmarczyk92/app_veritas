<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import UserLayoutNoCRMAccount from '@/Layouts/UserLayoutNoCRMAccount.vue';
import { Head } from '@inertiajs/vue3';

import PasswordUpdateForm from '@/Components/Password/PasswordUpdateForm.vue';

const props = defineProps({
    layout: String
});

const layout = props.layout == 'user' ? UserLayout : UserLayoutNoCRMAccount;

</script>

<template>

    <Head title="VeritasApp - zmiana hasła" />

    <component :is="layout">
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-3xl sm:tw-px-6 lg:tw-px-8 ">
                <PasswordUpdateForm />
            </div>
        </div>
    </component>
</template>
