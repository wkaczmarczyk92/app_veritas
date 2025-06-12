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
        title: 'Generowanie pliku z wypłatami',
        disabled: true
    },
]

const output = ref('')
const processing = ref(false)
const submit = async () => {
    processing.value = true;
    output.value = '';
    try {
        let response = await axios.post(route('advance.settings.manual.payout.request.update'));
        response = response.data;

        if (response.success) {
            output.value = response.output;
        }
    } catch (error) {
        console.error(error);
    } finally {
        processing.value = false;
    }
}

</script>

<template>

    <Head :title="`VeritasApp - ręczne generowanie pliku z wypłatami`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-relative">
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
        </div>
    </AdminLayout>
</template>