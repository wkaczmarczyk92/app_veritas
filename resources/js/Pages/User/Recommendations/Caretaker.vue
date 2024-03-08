<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';

import CaretakerRecommendation from '../Homepage/Parts/CaretakerRecommendation.vue';

const props = defineProps({
    bonus: {
        type: Object
    }
});

</script>

<template>
    <Head title="VeritasApp - poleć opiekunkę/a" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <CaretakerRecommendation :bonus="bonus" />
            </div>
        </div>
    </UserLayout>
</template>
