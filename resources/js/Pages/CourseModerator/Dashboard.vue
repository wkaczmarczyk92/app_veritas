<template>
    <Head title="VeritasApp - moderator kursów - pulpit" />
    <CourseModeratorLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>

        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3';
import SearchModal from './Modal/SearchModal.vue';

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue';
import MainContent from '@/Templates/HTML/MainContent.vue';

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: true
    },
    {
        title: 'Pulpit moderatora kursów',
        disabled: false,
        href: route('dashboard')
    }
]

</script>
