<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserLayout from '@/Layouts/UserLayout.vue'
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import TestTry from '@/Pages/Lessons/GermanLessons/TestTry.vue'

const props = defineProps({
    lesson: {
        type: Object,
        requried: true
    }
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

const destroy = async (id) => {
    if (confirm('Na pewno chcesz usunąć wybraną lekcję?')) {
        processing.value = true;

        try {
            let response = await axios.delete(route('german.lessons.destroy', {
                id: lesson.value.id
            }))

            response = response.data
            console.log('destroy response', response)

            alert_store.pushAlert(response)

            if (response.success) {
                router.get(route('german.lessons.index'))
            }
        } catch (error) {
            console.log(error)
            alert_store.exception()
        } finally {
            processing.value = false;
        }
    }
}

const destroy_test = async () => {
    if (confirm('Na pewno chcesz usunąć test?')) {
        processing.value = true;

        try {
            let response = await axios.delete(route('german.tests.destroy', {
                id: lesson.value.test[0].id
            }))

            response = response.data
            alert_store.pushAlert(response)

            if (response.success) {
                lesson.value.test = []
            }
        } catch (error) {
            console.error(error)
        } finally {
            processing.value = false;
        }
    }
}

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
                        <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-pb-1">
                            <div>
                                {{ lesson.name }}
                            </div>
                            <TestTry :lesson="lesson"></TestTry>
                            <!-- <div>
                                <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                                    <v-btn :color="lesson.visibility.id == 1 ? '#ea580c' : '#16a34a'"
                                        variant="outlined">
                                        <i class="fas fa-circle"
                                            :class="lesson.visibility.id == 1 ? 'tw-text-[#ea580c]' : 'tw-text-[#16a34a]'"></i>
                                        {{ lesson.visibility.name_pl }}
                                    </v-btn>
                                </div>
                            </div> -->
                        </div>
                    </template>
                    <v-card-text>
                        <div
                            class="tw-flex tw-flex-col md:tw-flex-row tw-gap-6 tw-mx-auto lg:tw-justify-start tw-justify-center">
                            <div class="tw-w-full lg:tw-w-1/3">
                                <video controls class="tw-w-[320px]">
                                    <source :src="`/lessons/${lesson.files[0].path}`">
                                </video>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </UserLayout>

</template>