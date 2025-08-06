<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Templates/Header.vue';
import { toRef, ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { format } from '@/Components/Functions/DateFormat.vue';
import CreateOralExam from '@/Components/Dialogs/CreateOralExam.vue';
import { use_oral_exam_store } from '@/Pinia/OralExamStore';

const props = defineProps({
    // users: {
    //     type: Object,
    //     required: true
    // },
    oral_exams: {
        type: Object,
        required: true
    }
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Egzamin niemieckiego',
        disabled: true
    },
    {
        title: 'Wyniki egzaminów ustnych',
        disabled: true
    },
]

const headers = [
    {
        title: 'ID',
        sortable: true,
        value: 'id'
    },
    {
        title: 'Użytkownik',
        value: 'user',
    },
    {
        title: 'Test zaliczony?',
        value: 'is_passed'
    },
    {
        title: 'Data testu',
        value: 'exam_at'
    },
    {
        title: 'Godzina testu',
        value: 'time'
    },
    // {
    //     title: 'Utworzono',
    //     value: 'created_at',
    // }
]

// const items = ref(props.test_results);
const user_id = ref(null)
const oral_exam_store = use_oral_exam_store()
oral_exam_store.init(props.oral_exams)
// oral_exam_store.set_users(props.users)

</script>

<template>

    <Head :title="`VeritasApp - harmonogram egazminów ustnych`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <Transition name="fade" mode="out-in">
            <div v-if="oral_exam_store.selected.length > 0" class="tw-p-4">
                <a href="#" class="tw-text-red-600 hover:tw-underline" @click="oral_exam_store.destroy()">
                    Usuń wybrane
                </a>
            </div>
        </Transition>
        <v-data-table :headers="headers" :items="oral_exam_store.exams">
            <template #item.created_at="{ item }">
                {{ format(item.created_at, true) }}
            </template>
            <template #item.is_passed="{ value }">
                <i v-if="value" class="fal fa-check-circle tw-text-green-600 tw-text-2xl"></i>
                <i v-else class="fal fa-times-circle tw-text-red-600 tw-text-2xl"></i>
            </template>
            <template #item.time="{ value }">
                {{ value }}:00
            </template>
            <template #item.exam_at="{ value }">
                {{ format(value) }}
            </template>
            <template #item.user="{ item }">
                <div class="tw-flex tw-flex-row tw-gap-4 tw-items-center">
                    <div>
                        <div v-if="item.user.user_profile_image && item.user.user_profile_image.path && item.user.user_profile_image.status == 3"
                            class="tw-relative tw-border-2 tw-border-gray-800 tw-shadow-xl profile-img profile-img-sm"
                            :style="`background-image: url(/user_profile_images/${item.user.user_profile_image.path});`">
                        </div>
                        <i v-else class="fa-solid fa-circle-user img-default img-default-sm"></i>
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-1">
                        <a :href="route('user', { id: item.user.id })"
                            class="tw-text-blue-600 hover:tw-underline hover:tw-text-blue-800">{{
                                item.user.user_profiles.full_name }}</a>
                        <div v-if="item.user.pesel" href="">PESEL: <span class="tw-font-bold">{{ item.user.pesel ?? '-'
                        }}</span></div>
                        <a v-else class="tw-text-purple-600 hover:tw-underline hover:tw-text-purple-800"
                            :href="`mailto:${item.user.email}`">{{ item.user.email }}</a>
                    </div>
                </div>
            </template>
        </v-data-table>
    </AdminLayout>
</template>