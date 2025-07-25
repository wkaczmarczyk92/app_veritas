<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import DialogAddTest from './DialogAddTest.vue';
import TestPreview from './TestPreview.vue';
import TestTry from './TestTry.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import { use_german_test_store } from '@/Pinia/Test/GermanTestStore';
import { use_processing_store } from '@/Pinia/ProcessingStore';

const props = defineProps({
    german_lesson: {
        type: Object,
        requried: true
    }
})

const german_test_store = use_german_test_store()
german_test_store.set_lesson(props.german_lesson)

const processing_store = use_processing_store()
const alert_store = AlertStore()

// const form = ref({
//     file: []
// })


// console.log('lesson', lesson.value)

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: true
    },
    {
        title: 'Lekcje niemieckiego'
        ,
        disabled: false,
        href: route('german.lessons.index')
    },
    {
        title: 'Ustawienia testu',
        disabled: true,
    },
]

// const submit = async () => {
//     processing.value = true

//     try {
//         let response = await axios.patch(route('german.tests.settings.update'), german_lesson.value)
//         response = response.data

//         alert_store.pushAlert(response)
//     } catch (error) {
//         console.error(error)
//         alert_store.exception()
//     } finally {
//         processing.value = false
//     }
// }

const destroy_test_data = async () => {
    processing_store.start()

    try {
        let response = await axios.delete(route('test.results.destroy.test.user.data'))
        response = response.data

        alert_store.pushAlert(response)
    } catch (error) {
        console.log(error)
        alert_store.exception()
    } finally {
        processing_store.stop()
    }
}

</script>

<template>

    <Head title="VeritasApp - Ustawienia testu" />

    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="">
            <div class="tw-max-w-full tw-mx-auto">
                <v-card title="Ustawienia testu" class="!tw-relative">
                    <Spinner v-if="processing_store.state" msg="Aktualizacja ustawień" />
                    <v-card-text class="!tw-pt-4">
                        <div class="tw-flex tw-flex-col tw-gap-2 tw-justify-start">
                            <div class="tw-mb-4">
                                <a :href="route('german.tests.show')" class="tw-text-blue-600 hover:tw-underline">
                                    Przejdź do testu
                                </a>
                            </div>
                            <div class="tw-mb-4">
                                <a href="#" class="tw-text-red-600 hover:tw-underline" @click="destroy_test_data()">
                                    Usuń dane testowe
                                </a>
                            </div>
                            <div class="tw-max-w-2xl">
                                <v-text-field v-model="german_test_store.german_lesson.name" disabled variant="outlined"
                                    label="Główna nazwa lekcji" class="tw-w-full tw-mb-4">
                                </v-text-field>
                                <v-text-field type="number" v-model="german_test_store.german_lesson.test_time"
                                    variant="outlined" label="Czas na wykonanie testu (w sekundach)"
                                    :error-messages="german_test_store?.errors?.['test_time'] ?? null"></v-text-field>
                                <v-text-field type="number" v-model="german_test_store.german_lesson.question_count"
                                    variant="outlined" label="Ilość pytań w teście"
                                    :error-messages="german_test_store?.errors?.['question_count'] ?? null"></v-text-field>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-actions>
                        <div class="tw-flex tw-flex-row tw-gap-2">
                            <v-btn @click="german_test_store.update_settings()" color="#16a34a" variant="tonal">
                                Zapisz ustawienia
                            </v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </div>
        </div>
    </AdminLayout>

</template>