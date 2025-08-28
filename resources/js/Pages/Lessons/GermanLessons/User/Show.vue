<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserLayout from '@/Layouts/UserLayout.vue'
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import TestTry from '@/Pages/Lessons/GermanLessons/TestTry.vue'
import { use_processing_store } from '@/Pinia/ProcessingStore';
import { use_german_lesson_store } from '@/Pinia/Lessons/GermanLessonStore';

const props = defineProps({
    lesson: {
        type: Object,
        requried: true
    },
    previous_lesson: {
        type: [Object, null],
        requried: true
    },
    next_lesson: {
        type: [Object, null],
        requried: true
    },
    is_last_lesson: {
        type: Boolean,
        requried: true
    },

})

const lesson = ref(props.lesson)
const alert_store = AlertStore()
const form = ref({
    file: []
})

const processing = ref(false)

console.log('lesson', lesson.value)

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
        title: props.lesson.name,
        disabled: true,
    },
]

const german_lesson_store = use_german_lesson_store()
german_lesson_store.set_lesson(props.lesson)

</script>

<template>

    <Head title="VeritasApp - Lekcje język niemieckiego" />

    <UserLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-mx-auto tw-max-w-7xl tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <v-card>
                    <Spinner v-if="processing" />
                    <template v-slot:title>
                        <div class="tw-flex tw-flex-row md:tw-flex-row tw-justify-center tw-pb-1">
                            <div>
                                {{ lesson.name }}
                            </div>
                        </div>
                    </template>
                    <v-card-text>
                        <div class="tw-flex tw-flex-row md:tw-flex-row tw-justify-between tw-pb-1">
                            <div class="tw-text-sm">
                                <a v-if="previous_lesson" :href="route('user.german.lessons.show', {id: previous_lesson.id})" class="tw-text-blue-600 hover:tw-underline tw-flex tw-gap-2 tw-items-center">
                                    <i class="fas fa-long-arrow-left"></i>
                                    Poprzednia lekcja
                                </a>
                            </div>
                            <div class="tw-text-sm">
                                <a v-if="next_lesson" :href="route('user.german.lessons.show', {id: next_lesson.id})" class="tw-text-blue-600 hover:tw-underline tw-flex tw-gap-2 tw-items-center">
                                    Następna lekcja
                                    <i class="fas fa-long-arrow-right"></i>
                                </a>
                                <a v-else :href="route('user.german.test.show')" class="tw-text-orange-600 tw-font-bold hover:tw-underline tw-flex tw-gap-2 tw-items-center">
                                    Test główny
                                    <i class="fas fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="tw-mt-6">
                            <TestTry :lesson="german_lesson_store.lesson"></TestTry>

                            <div
                                class="tw-mt-4 tw-flex tw-flex-col md:tw-flex-row tw-gap-6 tw-mx-auto lg:tw-justify-start tw-justify-center">
                                <div class="tw-w-full lg:tw-w-1/3">
                                    <video controls class="tw-w-[320px]">
                                        <source :src="`/lessons/${lesson.files[0].path}`">
                                    </video>
                                </div>
                            </div>
                        </div>
                        <div class="tw-text-center tw-flex tw-flex-col tw-my-16 tw-gap-6" v-if="is_last_lesson">
                            <div class="tw-text-xl">
                                Ostatnia lekcja za Tobą – przejdź do <span class="tw-font-bold">TESTU GŁÓWNEGO!</span>
                            </div>
                            <div>
                                <v-btn color="#ea580c" size="x-large" :href="route('user.german.test.show')">Rozwiąż TEST</v-btn>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </UserLayout>

</template>