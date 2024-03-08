<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

import ImportantInfo from './Homepage/Parts/ImportantInfo.vue';

const props = defineProps({
    recruiter: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <Head title="VeritasApp - ważne informacje" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <ImportantInfo :recruiter="recruiter" />
            </div>
        </div>
    </UserLayout>
</template>
