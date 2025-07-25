<script setup>

import UserLayout from '@/Layouts/UserLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { useTestStore } from '@/Pinia/TestStore'
import TestContent from '@/Pages/Lessons/GermanLessons/TestContent.vue'

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
    }
})

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
            <div class="tw-p-4 tw-mx-auto tw-max-w-8xl md:tw-max-w-5xl sm:tw-px-6 lg:tw-px-8">
                <div v-if="!can_take_test || (test_store.test_attempts >= 2 && !test_store.result)">
                    <v-alert color="warning">Możes spróbować wykonać tylko dwa testy dziennie. Spróbuj ponownie jutro.</v-alert>
                </div>
                <div v-else>
                    <v-alert color="info" v-if="props.questions.length == 0">Test chwilowo niedostępny. Spróbuj ponownie
                        później.</v-alert>
                    <TestContent v-else :questions="props.questions" :german_lesson="german_lesson" />
                </div>
            </div>
        </div>
    </UserLayout>

</template>