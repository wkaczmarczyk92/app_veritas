<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import DialogAddTest from './DialogAddTest.vue';
import TestPreview from './TestPreview.vue';
import TestTry from './TestTry.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import { to_array } from '@/Composables/Formatter/ArrayFormatter';
import { use_processing_store } from '@/Pinia/ProcessingStore';
import { use_german_lesson_store } from '@/Pinia/Lessons/GermanLessonStore';


const props = defineProps({
    lesson: {
        type: Object,
        requried: true
    },
    visibilities: {
        type: [Array, Object],
        required: true
    }
})


const visibilities = to_array(props.visibilities)
const alert_store = AlertStore()
const processing_store = use_processing_store()

const german_lesson_store = use_german_lesson_store()
german_lesson_store.set_lesson(props.lesson)
german_lesson_store.set_selected_visibility()

// const selected_visibility = ref(german_lesson_store.lesson.visibility.id)

const form = ref({
    file: []
})

console.log('visibility', visibilities)


const processing = ref(false)

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
        title: german_lesson_store.lesson.name,
        disabled: true,
    },
]

const destroy_test = async () => {
    if (confirm('Na pewno chcesz usunąć test?')) {
        processing.value = true;

        try {
            let response = await axios.delete(route('german.tests.destroy', {
                id: german_lesson_store.lesson.test[0].id
            }))

            response = response.data
            alert_store.pushAlert(response)

            if (response.success) {
                german_lesson_store.lesson.test = []
            }
        } catch (error) {
            console.error(error)
        } finally {
            processing.value = false;
        }
    }
}

const visibility_class = (visibility) => {
    switch (visibility) {
        case 1:
            return 'tw-text-[#ea580c]';
        case 3:
            return 'tw-text-[#16a34a]';
        case 4:
            return 'tw-text-[#2563eb]';
        default:
            return '';
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
            <div class="tw-max-w-full tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <v-card class="!tw-relative">
                    <Spinner v-if="processing_store.state" />
                    <template v-slot:title>
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-pb-1">
                            <div>
                                {{ german_lesson_store.lesson.name }}
                            </div>
                            <div>
                                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-py-2">
                                    <Transition mode="out-in" name="fade">
                                        <v-btn color="#16a34a" v-if="german_lesson_store.selected_visibility != german_lesson_store.lesson.visibility.id" @click="german_lesson_store.update_visibility()">Aktualizuj</v-btn>
                                    </Transition>
                                    <v-select variant="outlined" density="comfortable" label="Widoczność"
                                        :items="visibilities" v-model="german_lesson_store.selected_visibility" item-value="id"
                                        item-title="name_pl" hide-details class="!tw-w-[300px]">
                                        <template v-slot:prepend-inner>
                                            <i class="fas fa-circle" :class="visibility_class(german_lesson_store.lesson.visibility.id)"></i>
                                        </template>
                                    </v-select>
                                </div>
                            </div>
                        </div>
                    </template>
                    <v-card-text>
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-gap-6">
                            <div class="tw-w-1/3" v-if="german_lesson_store.lesson.files && german_lesson_store.lesson.files[0]">
                                <video width="320" height="240" controls>
                                    <source :src="`/lessons/${german_lesson_store.lesson.files[0].path}`">
                                </video>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-2 tw-grow tw-justify-between">
                                <div>
                                    <Flex212 title="Numer lekcji" :value="german_lesson_store.lesson.order" />
                                    <Flex212 title="Widoczność" :value="german_lesson_store.lesson.visibility.name_pl" />
                                    <Flex212 title="Dodane przez" :value="german_lesson_store.lesson.user.email" />
                                    <div class="tw-mt-10 tw-flex tw-flex-row tw-gap-2"
                                        v-if="german_lesson_store.lesson.test && german_lesson_store.lesson.test[0]">
                                        <TestPreview :lesson="german_lesson_store.lesson" />
                                        <TestTry :lesson="german_lesson_store.lesson" :is_admin="true" />
                                    </div>
                                </div>

                                <div class="tw-flex tw-flex-row tw-gap-2 tw-mt-4">
                                    <v-btn color="#dc2626" @click="german_lesson_store.destroy(german_lesson_store.lesson.id)">Usuń</v-btn>
                                    <DialogAddTest v-if="german_lesson_store.lesson.test.length == 0" :lesson="german_lesson_store.lesson"
                                        @update-test="german_lesson_store.lesson.test.push($event)" />
                                    <v-btn v-else variant="outlined" @click="destroy_test()">Usuń test</v-btn>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AdminLayout>

</template>