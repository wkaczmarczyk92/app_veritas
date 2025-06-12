<template>

    <Head title="VeritasApp - moderator kursów - edycja kompendium" />
    <CourseModeratorLayout class="tw-bg-indigo-100">
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4">
                <v-card :loading="form.progress" title="Edytuj lekcję" subtitle="podstawowe dane" class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-file-circle-plus tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <TInput label="Nazwa kompendium" placeholder="wpisz nazwę kompendium..."
                            :error="errors.name ? errors.name[0] : null" v-model:model_value="form.name"></TInput>
                        <TInput label="Czas czytania" placeholder="wpisz długość czasu czytania w minutach..."
                            type="text" :error="errors.time_to_read ? errors.time_to_read[0] : null"
                            v-model:model_value="form.time_to_read">
                        </TInput>
                        <v-select :items="visibility" item-title="name_pl" item-value="id" label="Widoczność lekcji"
                            hide-details variant="outlined" v-model="form.visibility_id" />
                        <InputError :message="errors.visibility_id ? errors.visibility_id[0] : ''" class="tw-mt-1" />
                    </v-card-text>
                    <v-card-actions>
                        <v-btn variant="outlined" color="#0284c7" size="large" @click="submit()"
                            :disabled="form.progress">
                            <i class="fa-light fa-floppy-disk-circle-arrow-right tw-mr-2"></i>
                            Aktualizuj lekcję
                        </v-btn>
                        <v-btn variant="tonal" color="#dc2626" size="large" :href="route('course_moderator.vademecum.lesson.show', {
                            vademecum_id: props.lesson.lessonable_id,
                            lesson_id: props.lesson.id
                        })" :disabled="form.progress">
                            <i class="fa-solid fa-xmark tw-mr-2"></i>
                            Zakończ edycję
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <v-card :loading="form.progress" title="Treść kompendium" subtitle="uzupełnij treść kompendium"
                    class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-sharp fa-solid fa-feather tw-text-2xl"></i>
                    </template>
                    <div class="tw-flex tw-flex-col tw-gap-4 tw-mt-6">
                        <div>
                            <Preview :progress="form.progress" :form="form"></Preview>
                        </div>
                        <div>
                            <MCEEditor v-model:modelValue="form.description"
                                @update-value="form.description = $event" />
                            <InputError :message="errors.description ? errors.description[0] : ''" class="tw-mt-1">
                            </InputError>
                        </div>
                    </div>
                </v-card>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

import TInput from '@/Composables/Form/TInput.vue'
import Editor from '@tinymce/tinymce-vue'
import InputError from '@/Components/InputError.vue'

import Preview from '../Compendium/Preview.vue'

import MCEEditor from '@/Composables/MCEEditor.vue'
import { upload_file } from '@/Composables/UploadFilesTinyMCE'

const props = defineProps({
    lesson: {
        type: [Object, null],
        required: true
    },
    visibility: {
        type: Object,
        required: true
    }
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Vademeca',
        disabled: false,
        href: route('course_moderator.vademecum.index')
    },
    {
        title: props.lesson.lessonable.name ?? 'Błąd',
        disabled: false,
        href: route('course_moderator.vademecum.show', {
            id: props.lesson.lessonable.id
        })
    },
    {
        title: 'Lekcja',
        disabled: true,
    },
    {
        title: props.lesson.name,
        disabled: false,
        href: route('course_moderator.vademecum.lesson.show', {
            vademecum_id: props.lesson.lessonable.id,
            lesson_id: props.lesson.id
        })
    },
    {
        title: 'Edytuj lekcję',
        disabled: true
    }
]

const alert_store = AlertStore()

const form = ref({
    name: props.lesson.name,
    description: props.lesson.description,
    time_to_read: props.lesson.time_to_read,
    visibility_id: props.lesson.visibility_id,
    progress: false
})
const errors = ref({})

const submit = async () => {
    form.value.progress = true
    errors.value = {}

    let response = await axios.patch(route('course_moderator.compendium.lesson.update', {
        id: props.lesson.id
    }), { ...form.value })
    response = response.data

    if (response.errors) {
        console.log('errors', response.errors)
        errors.value = response.errors
        form.value.progress = false
    }

    if (response.success) {
        setTimeout(() => {
            router.visit(route('course_moderator.vademecum.lesson.show', {
                vademecum_id: props.lesson.lessonable.id,
                lesson_id: props.lesson.id
            }))
        }, 2000)
    }

    alert_store.pushAlert(response.alert_type, response.msg)
}

</script>
