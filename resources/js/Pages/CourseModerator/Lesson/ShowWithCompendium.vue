<template>

    <Head title="VeritasApp - moderator kursów - kompendium" />
    <CourseModeratorLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <Processing v-if="delete_processing" msg="Usuwanie lekcji..." />

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4">
                <v-card :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-px-4" :title="lesson.name"
                    :subtitle="'Utworzono: ' + format(lesson.created_at)">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-person-chalkboard tw-text-2xl"></i>
                    </template>
                    <template v-slot:title>
                        <div class="tw-flex tw-flex-row tw-justify-between">
                            <div>{{ lesson.name }}</div>
                            <v-btn color="#0284c7" text="Podgląd" variant="outlined" :disabled="false" size="small"
                                :href="route('course_moderator.compendium.lesson.edit', {
                                    compendium_id: lesson.lessonable.id,
                                    lesson_id: lesson.id
                                })">
                                <template v-slot:prepend>
                                    <i class="fa-light fa-file-pen"></i>
                                </template>
                                Edytuj
                            </v-btn>
                        </div>
                    </template>
                    <template v-slot:subtitle>
                        <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2">
                            <div>
                                {{ lesson.lessonable.name }}
                            </div>
                            <!-- <div>
                                Utworzono: {{ format(lesson.created_at) }}
                            </div> -->
                        </div>
                        <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-2">
                            <v-chip variant="outlined">
                                Utworzono: {{ format(lesson.created_at) }}
                            </v-chip>
                            <v-chip variant="outlined">
                                Ilość testów: 10
                            </v-chip>
                            <v-chip variant="outlined" :color="lesson.visibility.id == 1 ? '#ea580c' : '#16a34a'">
                                {{ lesson.visibility.name_pl }}
                            </v-chip>
                        </div>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <div v-html="lesson.description" class="tw-mt-6"></div>
                    </v-card-text>
                </v-card>
                <v-card :color="'white'" class="tw-mb-4 tw-shadow-2xl !tw-px-4" title="Testy">
                    <template v-slot:prepend>
                        <i class="fa-sharp fa-solid fa-graduation-cap tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <div>
                            <!-- <v-btn color="#22c55e" text="Podgląd" variant="tonal" :disabled="false"
                                :href="route('course_moderator.compendium.lesson.create', { compendium: compendium })"><template
                                    v-slot:prepend>
                                    <i class="fa-sharp fa-solid fa-layer-plus"></i>
                                </template>
                                Dodaj nową lekcję
                            </v-btn> -->
                        </div>
                    </v-card-text>
                </v-card>
            </div>
            <div class="tw-flex tw-flex-row-reverse">
                <v-btn color="#dc2626" text="Podgląd" size="small" variant="tonal" :disabled="false"
                    @click="delete_item(lesson)">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-trash"></i>
                    </template>
                    Usuń lekcję
                </v-btn>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>
import {
    Head,
    router
} from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import {
    ref,
    watch,
    onMounted
} from 'vue'
import TableLink from '@/Templates/HTML/TableLink.vue';

import {
    format
} from '@/Components/Functions/DateFormat.vue';

import {
    AlertStore
} from '@/Pinia/AlertStore'
import axios from 'axios';

import { destroy_lesson } from '@/Pages/CourseModerator/Lesson/DestroyLesson'
import Processing from '@/Composables/Processing.vue'


const props = defineProps({
    lesson: {
        type: Object,
        required: true
    }
})

const alert_store = AlertStore()

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Kompendia',
        disabled: false,
        href: route('course_moderator.compendium.index')
    },
    {
        title: props.lesson.lessonable.name,
        disabled: false,
        href: route('course_moderator.compendium.show', {
            id: props.lesson.lessonable.id

        })
    },
    {
        title: 'Lekcja',
        disabled: true,
    },
    {
        title: props.lesson.name,
        disabled: true
    }
]

const delete_processing = ref(false)

const delete_item = async (lesson) => {
    delete_processing.value = true
    await destroy_lesson(lesson)
    delete_processing.value = true
}

// const delete_lesson = async () => {
//     if (confirm('Na pewno chcesz usunąć lekcję?')) {
//         let response = await axios.delete(route('course_moderator.lesson.destroy', {
//             lesson_id: props.lesson.id
//         }))

//         response = response.data

//         if (response.success) {
//             route.visit(route('compendium.show', {
//                 id: props.lesson.lessonable.id
//             }))
//         }

//         alert_store.pushAlert(response)
//     }
// }


</script>

<style>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
