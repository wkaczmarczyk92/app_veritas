<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore';

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
        title: 'Dodaj nową',
        disabled: false,
        href: route('german.lessons.create')
    },
]

const form_init = () => {
    return {
        file: [],
        order: '',
        name: '',

    }
}

const form = ref(form_init())
const errors = ref({})

const alert_store = AlertStore()

const processing = ref(false)

const submit = async () => {
    errors.value = {}
    processing.value = true
    const form_data = new FormData()
    form_data.append('lesson', form.value.file[0])
    form_data.append('order', form.value.order)
    form_data.append('name', form.value.name)

    console.log(form_data)
    console.log(form.value.file)

    try {
        let response = await axios.post(route('german.lessons.store'), form_data, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        response = response.data

        if (response.success) {
            form.value = form_init()
        }

        if (response.errors) {
            errors.value = response.errors
        }

        alert_store.pushAlert(response)
        processing.value = false

    } catch (error) {
        console.log(error)
        processing.value = false
    }
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
        <div class="tw-py-12">
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8 tw-relative">
                <div v-if="processing" class="tw-absolute tw-w-full tw-h-full tw-bg-black tw-z-10 tw-opacity-90">
                    <div
                        class="tw-absolute tw-text-gray-100 tw-top-[50%] -tw-translate-y-[50%] tw-left-[50%] -tw-translate-x-[50%]">
                        <div class="tw-flex tw-flex-row tw-items-center">
                            <svg class="tw-animate-spin -tw-ml-1 tw-mr-3 tw-h-5 tw-w-5 tw-text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="tw-opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4">
                                </circle>
                                <path class="tw-opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Wgrywanie...
                        </div>
                    </div>
                </div>
                <div class="tw-flex tw-flex-col tw-gap-2">
                    <v-text-field label="Nazwa lekcji" variant="outlined" v-model="form.name"
                        :error-messages="errors && errors['name'] ? errors['name'] : ''"></v-text-field>
                    <v-text-field label="Numer lekcji" variant="outlined" v-model="form.order"
                        :error-messages="errors && errors['order'] ? errors['order'] : ''"></v-text-field>
                    <div class="">
                        <v-file-upload clearable density="default" browse-text="Wybierz pliki" divider-text="lub"
                            title="Upuść pliki tutaj" v-model="form.file"></v-file-upload>
                    </div>
                </div>
                <div class="tw-mt-2">
                    <v-btn color="#16a34a" @click="submit()">Wgraj lekcję</v-btn>
                </div>
            </div>
        </div>
    </AdminLayout>

</template>