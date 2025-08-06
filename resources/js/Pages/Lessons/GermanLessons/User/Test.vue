<script setup>

import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { useTestStore } from '@/Pinia/TestStore'
import TestContent from '@/Pages/Lessons/GermanLessons/TestContent.vue'
import { use_oral_exam_store } from '@/Pinia/OralExamStore';
import CreateOralExam from '@/Components/Dialogs/CreateOralExam.vue';

const props = defineProps({
    questions: {
        type: Object,
        requried: true
    },
    german_lesson: {
        type: Object,
        requried: true
    },
    can_take_test: {
        type: Boolean,
        required: true
    },
    test_attempts: {
        type: Number,
        required: true
    },
    test_passed: {
        type: Boolean,
        required: true
    },
    oral_exam_passed: {
        type: Boolean,
        required: true
    },
    has_any_oral_exam: {
        type: Boolean,
        required: true
    },

})
const oral_exam_store = use_oral_exam_store()
oral_exam_store.set_user_settings(props.oral_exam_passed, props.has_any_oral_exam)

// const oral_exam_passed = ref(props.oral_exam_passed)
// const has_any_oral_exam = ref(props.has_any_oral_exam)

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
        title: 'Rozwiąż test',
        disabled: true,
    },
]

console.log('url', usePage().url)

const test_store = useTestStore()
// const test_attempts = ref(props.test_attempts)

try {
    console.log('prrops questions', props.questions)
    test_store.reset(false)
    test_store.set_test_attempts(props.test_attempts)
    test_store.init(props.questions, null, props.test_attempts)
} catch (error) {
    console.log(error)
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

            <div class="tw-p-4 tw-mx-auto tw-max-w-8xl md:tw-max-w-5xl sm:tw-px-6 lg:tw-px-8" v-if="test_passed">
                <v-alert color="success">Zdałeś/aś już test WWW.</v-alert>
                <v-alert v-if="oral_exam_store.oral_exam_passed" color="success" class="tw-mt-4">Zdałeś/aś już test ustny.</v-alert>
                <div class="tw-flex tw-justify-center tw-mt-10"
                    v-if="!oral_exam_store.oral_exam_passed && !oral_exam_store.has_any_oral_exam">
                    <CreateOralExam :by_user="true" @got_date="has_any_oral_exam=true" />
                </div>
            </div>
            <div v-else class="tw-p-4 tw-mx-auto tw-max-w-8xl md:tw-max-w-5xl sm:tw-px-6 lg:tw-px-8">
                <div v-if="!can_take_test || (test_store.test_attempts >= 2 && !test_store.result)">
                    <v-alert color="warning">Możes spróbować wykonać tylko dwa testy dziennie. Spróbuj ponownie
                        jutro.</v-alert>
                </div>
                <div v-else>
                    <v-alert color="info" v-if="props.questions.length == 0">Test chwilowo niedostępny. Spróbuj ponownie
                        później.</v-alert>
                    <TestContent v-else :questions="props.questions" :german_lesson="german_lesson">
                        <Transition name="fade" mode="out-in">
                            <div class="tw-flex tw-justify-center tw-mt-10"
                                v-if="test_store.is_passed && !test_store.oral_exam_set">
                                <CreateOralExam :by_user="true" />
                            </div>
                        </Transition>
                    </TestContent>
                </div>
            </div>
        </div>
    </UserLayout>

</template>