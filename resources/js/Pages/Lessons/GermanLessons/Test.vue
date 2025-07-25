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
    },
    german_lesson: {
        type: Object,
        requried: true
    },
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

try {
    test_store.init(props.questions)
} catch (error) {
    console.log(error)
}

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
        <div class="tw-max-w-full tw-mx-auto">
            <v-alert color="info" v-if="props.questions.length == 0">Brak aktywnych lekcji z któych można pobrać pytania
                testowe.</v-alert>
            <TestContent v-else :questions="props.questions" :german_lesson="german_lesson" :is_admin="true" />
        </div>
    </AdminLayout>

</template>