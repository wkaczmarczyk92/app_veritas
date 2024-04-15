<template>

    <Head title="VeritasApp - moderator kursów - dodaj kompendium" />
    <CourseModeratorLayout class="tw-bg-indigo-100">
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-2 tw-grid-cols-1 tw-gap-4">
                <v-card :loading="form.progress" title="Dodaj nowe vademecum" subtitle="podstawowe dane"
                    class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-file-circle-plus tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <TInput label="Nazwa vademecum" placeholder="wpisz nazwę vademecum..."
                            :error="errors.name ? errors.name[0] : null" v-model:model_value="form.name" />
                        <TInput label="Czas czytania" placeholder="wpisz długość czasu czytania w minutach..."
                            type="text" :error="errors.time_to_read ? errors.time_to_read[0] : null"
                            v-model:model_value="form.time_to_read" />

                        <v-select :items="visibility" item-title="name_pl" item-value="id" label="Widoczność vademecum"
                            hide-details variant="outlined" v-model="form.visibility_id" />
                        <InputError :message="errors.visibility_id ? errors.visibility_id[0] : ''" class="tw-mt-1" />

                        <v-divider :thickness="4" class="border-opacity-100 tw-my-6" />

                        <h2 class="tw-text-base">Vademecum dostępne dla</h2>
                        <v-select clearable chips label="Wybierz role" :items="roles" multiple item-title="name_pl"
                            item-value="id" variant="outlined" hide-details v-model="form.permissions.roles" />

                        <v-select clearable chips label="Wybierz oddziały" :items="company_branches" multiple
                            item-title="name" item-value="id" variant="outlined" hide-details
                            v-model="form.permissions.company_branches" />
                    </v-card-text>
                    <v-card-actions>
                        <v-btn variant="outlined" color="#16a34a" size="large" @click="submit()"
                            :disabled="form.progress">
                            <i class="fa-solid fa-plus tw-mr-2"></i>
                            Dodaj vademecum
                        </v-btn>
                    </v-card-actions>
                </v-card>
                <v-card :loading="form.progress" title="Treść vademecum" subtitle="uzupełnij treść kompendium"
                    class="!tw-p-4">
                    <template v-slot:prepend>
                        <i class="fa-sharp fa-solid fa-feather tw-text-2xl"></i>
                    </template>
                    <div class="tw-flex tw-flex-col tw-gap-4 tw-mt-6">
                        <div>
                            <Preview :progress="form.progress" :form="form" />
                        </div>
                        <div>
                            <MCEEditor v-model:modelValue="form.description"
                                @update-value="form.description = $event" />
                            <InputError :message="errors.description ? errors.description[0] : ''" class="tw-mt-1" />
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

import MCEEditor from '@/Composables/MCEEditor.vue'

import TInput from '@/Composables/Form/TInput.vue'
import Editor from '@tinymce/tinymce-vue'
import InputError from '@/Components/InputError.vue'

import Preview from '@/Pages/CourseModerator/Compendium/Preview.vue'

import { upload_file } from '@/Composables/UploadFilesTinyMCE'

const props = defineProps({
    visibility: {
        type: Object,
        required: true
    },
    company_branches: {
        type: Object,
        required: true,
    },
    roles: {
        type: Object,
        required: true,
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
        title: 'Dodaj vademecum',
        disabled: true
    }
]

const form_init = () => {
    return {
        name: '',
        description: '',
        time_to_read: '',
        visibility_id: 1,
        progress: false,
        permissions: {
            roles: [],
            company_branches: []
        },
    }
}

const alert_store = AlertStore()

const form = ref(form_init())
const errors = ref({})


const submit = async () => {
    form.value.progress = true
    errors.value = {}

    let response = await axios.post(route('course_moderator.vademecum.store'), { ...form.value })
    response = response.data

    if (response.errors) {
        console.log('errors', response.errors)
        errors.value = response.errors
        form.value.progress = false
    }

    alert_store.pushAlert(response.alert_type, response.msg)

    if (response.success) {
        router.visit(route('course_moderator.vademecum.show', {
            id: response.id
        }))
    }
}

</script>
