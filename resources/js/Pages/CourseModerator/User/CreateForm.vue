<template>
    <Head title="VeritasApp - moderator kursów - dodaj użytkownika" />
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

import { Head } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Użytkownicy',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Dodaj użytkownika',
        disabled: false,
        href: route('course_moderator.create_user')
    }
]

</script>
