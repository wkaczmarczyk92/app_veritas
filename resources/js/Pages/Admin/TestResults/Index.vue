<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Templates/Header.vue';
import { toRef, ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { format } from '@/Components/Functions/DateFormat.vue';

const props = defineProps({
    test_results: {
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
        title: 'Wyniki testów',
        disabled: true
    },
    {
        title: 'Testy niemieckiego',
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
        title: 'Status testu',
        value: 'is_passed'
    },
    {
        title: 'Wynik testu',
        value: 'score'
    },
    {
        title: 'Utworzono',
        value: 'created_time'
    },
    // {
    //     title: 'Termin testu ustnego',
    //     value: 'oral_exam_date',
    // },
    // {
    //     title: 'Wynik testu ustnego',
    //     value: 'oral_exam_score',
    // },
]

const items = ref(props.test_results);

</script>

<template>

    <Head :title="`VeritasApp - wyniki testów niemieckiego`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <v-data-table :headers="headers" :items="items">
            <template #item.score="{ value }">
                {{value}}%
            </template>
            <template #item.created_time="{ item }">
                {{ format(item.created_at, true) }}
            </template>
            <template #item.oral_exam_date="{ item }">
                
            </template>
            <template #item.is_passed="{ value }">
                <span v-if="value" class="tw-text-green-500 tw-font-bold">ZALICZONY</span>
                <span v-else class="tw-text-red-500">Niezaliczony</span>   
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
        <!-- <div class="tw-relative">
            <div v-if="processing" class="tw-absolute tw-w-full tw-h-full tw-bg-black tw-opacity-70 tw-z-10">
                <div
                    class="tw-absolute tw-top-[50%] tw-left-[50%] tw-text-gray-200 -tw-translate-x-[50%] -tw-translate-y-[50%]">
                    Generowanie
                    pliku...
                </div>
            </div>
            <v-card title="Wygeneruj plik z wypłatami">
                <v-card-text>
                    <div class="tw-flex tw-flex-col tw-gap-2">
                        <div>
                            <v-btn @click="submit()">Generuj plik</v-btn>
                        </div>
                        <div v-if="output" v-html="output"></div>
                    </div>
                </v-card-text>
            </v-card>
        </div> -->
    </AdminLayout>
</template>