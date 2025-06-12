<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { useTestStore } from '@/Pinia/TestStore'
import TestContent from '@/Pages/Lessons/GermanLessons/TestContent.vue'

const props = defineProps({
    questions: {
        type: Object,
        requried: true
    }
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: true
    },
    {
        title: 'Lekcje niemieckiego',
        disabled: false,
        href: route('german.lessons.index')
    },
    {
        title: 'Rozwiąż test',
        disabled: true,
    },
]

const test_store = useTestStore()
test_store.init(props.questions)

</script>

<template>

    <Head title="VeritasApp - Lekcje język niemieckiego" />

    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <TestContent :questions="props.questions" :is_admin="true" />
            </div>
        </div>
    </AdminLayout>

</template>