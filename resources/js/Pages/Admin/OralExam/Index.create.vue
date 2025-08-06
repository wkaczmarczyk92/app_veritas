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
    users: {
        type: Object,
        required: true
    },
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
        title: 'Harmonogram egzaminów ustnych',
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
        value: 'score'
    },
    {
        title: 'Data testu',
        value: 'exam_at'
    },
    {
        title: 'Godzina testu',
        value: 'time'
    },
    {
        title: 'Utworzono',
        value: 'created_at',
    }
]

// const items = ref(props.test_results);
const user_id = ref(null)
const oral_exam_store = use_oral_exam_store()
oral_exam_store.init(props.oral_exams)
oral_exam_store.set_users(props.users)

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
        <Transition mode="out-in" name="fade">
            <v-card v-if="oral_exam_store.available_users.length == 0">
                <v-card-text>
                    <v-alert color="info">Brak użytkowników do umówienia na termin egzaminu ustnego.</v-alert>
                </v-card-text>
            </v-card>
            <v-card v-else>
                <template v-slot:title>
                    <div>Zapisz użytkownika na egzamin ustny ({{ oral_exam_store.available_users.length }})</div>
                </template>
                <v-card-text>
                    <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                        <v-autocomplete v-model="oral_exam_store.selected_user_id" hide-details variant="outlined"
                            label="Wybierz użytkownika" :items="oral_exam_store.available_users"
                            item-title="user_profiles.full_name" item-value="id"></v-autocomplete>
                        <CreateOralExam />
                    </div>
                </v-card-text>
            </v-card>
        </Transition>

        <Transition name="fade" mode="out-in">
            <div v-if="oral_exam_store.selected.length > 0" class="tw-p-4">
                <a href="#" class="tw-text-red-600 hover:tw-underline" @click="oral_exam_store.destroy()">
                    Usuń wybrane
                </a>
            </div>
        </Transition>
        <v-data-table v-model="oral_exam_store.selected" show-select :headers="headers" :items="oral_exam_store.exams">
            <template #item.scrore_www="{ item }">
                <span v-if="item.is_passed" class="tw-text-green-500 tw-font-bold">ZALICZONY - {{ item.score }}% </span>
                <span v-else class="tw-text-red-500">Niezaliczony - {{ item.score }}% </span>
            </template>
            <template #item.created_at="{ item }">
                {{ format(item.created_at, true) }}
            </template>
            <template #item.score="{ item }">
                <div v-if="item.score">{{ item.score }}%</div>
                <div v-else class="tw-flex tw-flex-row tw-gap-1">
                    <a href="#" class="tw-text-green-600 hover:tw-underline"
                        @click="oral_exam_store.set_as_passed(item.id)">Zalicz test</a>
                    <div>/</div>

                    <a href="#" class="tw-text-red-600 hover:tw-underline"
                        @click="oral_exam_store.set_as_failure(item.id)">Oblej test</a>
                </div>
            </template>
            <template #item.exam_at="{ value }">
                {{ format(value) }}
            </template>
            <template #item.time="{ value }">
                {{ value }}:00
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