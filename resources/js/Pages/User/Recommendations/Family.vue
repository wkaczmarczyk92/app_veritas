<script setup>
import UserLayout from '@/Layouts/UserLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import FamilyRecommendation from '../Homepage/Parts/FamilyRecommendation.vue';

const caretaker_recommendations_modal = ref(false);
const props = defineProps({
    bonus: {
        type: Object
    }
});
</script>

<template>
    <Head title="VeritasApp - poleć rodzinę" />

    <UserLayout>
        <div class="tw-py-12">
            <div class="tw-p-4 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-8 ">
                <FamilyRecommendation :bonus="bonus" />
            </div>
        </div>
    </UserLayout>
</template>
