<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Templates/Header.vue';
import { toRef, ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Ustawienia zaawansowane',
        disabled: true
    },
    {
        title: 'Synchronizacja poziomu',
        disabled: true
    },
]

const output = ref('')
const processing = ref(false)

const sync_all = async () => {
    if (confirm('Na pewno chcesz uruchomić synchronizację poziomów dla wszystkich użytkowników?')) {
        processing.value = true
        console.log('synchronizacja')
        let response = await axios.post(route('app.settings.sync.level.update'))
        response = response.data

        console.log('sync all', response)

        if (response.success) {
            output.value = response.buffor
        }


    }

    processing.value = false
}

</script>

<template>

    <Head :title="`VeritasApp - synchronizacja poziomu`" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="">
            <div class="tw-relative tw-flex tw-flex-col tw-gap-2">
                <div v-if="processing" class="tw-absolute tw-w-full tw-h-full tw-bg-black tw-z-[10] tw-opacity-70">
                </div>
                <v-card>
                    <v-card-text>
                        <div class="tw-flex tw-flex-row tw-gap-2">
                            <v-btn @click="sync_all()" color="#2563eb">Synchronizuj wszystkie poziomy</v-btn>
                            <v-btn color="#9333ea">Synchronizuj jednego użytkownika</v-btn>
                        </div>
                    </v-card-text>
                </v-card>
                <div>
                    <v-card title="Output:" class="!tw-p-4">
                        <v-card-text class="tw-h-[400px] tw-overflow-y-scroll !tw-px-10">
                            <div v-html="output"></div>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn v-if="output" color="#dc2626" @click="output = ''">
                                <template v-slot:append>
                                    <i class="fas fa-times"></i>
                                </template>
                                Output
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>