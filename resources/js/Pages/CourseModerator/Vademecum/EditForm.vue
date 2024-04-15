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
                <v-card :loading="form.progress" title="Edytuj kompendium" subtitle="podstawowe dane" class="!tw-p-4">
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
                        <v-btn variant="outlined" color="#0284c7" size="large" @click="submit()"
                            :disabled="form.progress">
                            <i class="fa-light fa-floppy-disk-circle-arrow-right tw-mr-2"></i>
                            Aktualizuj vademecum
                        </v-btn>
                        <v-btn variant="outlined" color="#dc2626" size="large" :href="route('course_moderator.vademecum.show', {
                            id: vademecum.id
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

import TInput from '@/Composables/Form/TInput.vue'
import InputError from '@/Components/InputError.vue'

import Preview from '@/Pages/CourseModerator/Compendium/Preview.vue'


import MCEEditor from '@/Composables/MCEEditor.vue'

const props = defineProps({
    vademecum: {
        type: [Object, null],
        required: true
    },
    visibility: {
        type: Object,
        required: true
    },
    company_branches: {
        type: Object,
        required: true
    },
    roles: {
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
        title: props.vademecum.name ?? 'Błąd',
        disabled: false,
        href: route('course_moderator.vademecum.show', {
            id: props.vademecum.id
        })
    },
    {
        title: 'Edytuj vademecum',
        disabled: true
    }
]

const alert_store = AlertStore()

const form = ref({
    name: props.vademecum.name,
    description: props.vademecum.description,
    time_to_read: props.vademecum.time_to_read,
    visibility_id: props.vademecum.visibility_id,
    permissions: {
        roles: props.vademecum.roles_id,
        company_branches: props.vademecum.company_branches_id
    },
    progress: false
})
const errors = ref({})

const submit = async () => {
    form.value.progress = true
    errors.value = {}

    let response = await axios.patch(route('course_moderator.compendium.update', {
        id: props.vademecum.id
    }), { ...form.value })
    response = response.data

    if (response.errors) {
        console.log('errors', response.errors)
        errors.value = response.errors
        form.value.progress = false
    }

    if (response.success) {
        setTimeout(() => {
            router.visit(route('course_moderator.vademecum.show', {
                id: props.vademecum.id
            }))
        }, 2000)
    }

    alert_store.pushAlert(response.alert_type, response.msg)
}

</script>
