<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps({
    lesson: {
        type: Object,
        requried: true
    }
})

const lesson = ref(props.lesson)
const _lesson = ref(JSON.parse(JSON.stringify(lesson.value)))

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
        disabled: false,
        href: route('german.lessons.show', { id: lesson.value.id })
    },
    {
        title: 'Edycja',
        disabled: true,
    },
]

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
                <v-card>
                    <template v-slot:title>
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-pb-1">
                            <div>
                                Edycja - {{ _lesson.name }}
                            </div>
                            <div>
                                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                                    <v-btn :color="lesson.visibility.id == 1 ? '#ea580c' : '#16a34a'"
                                        variant="outlined">
                                        <i class="fas fa-circle"
                                            :class="lesson.visibility.id == 1 ? 'tw-text-[#ea580c]' : 'tw-text-[#16a34a]'"></i>
                                        {{ lesson.visibility.name_pl }}
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </template>
                    <v-card-text>
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-gap-2">
                            <div class="tw-w-1/3">
                                <video width="320" height="240" controls>
                                    <source :src="`/lessons/${lesson.files[0].path}`">
                                </video>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-2">
                                <div>Widoczność: {{ lesson.visibility.name_pl }}</div>
                                <div>Dodane przez: {{ lesson.user.email }}</div>

                                <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-4">


                                </div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AdminLayout>

</template>