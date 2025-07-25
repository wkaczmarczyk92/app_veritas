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
        title: 'Punkty',
        disabled: true
    },
]

const output = ref('')
const processing = ref(false)
const form = ref({
    pesel: '',
    month: '',
    year: ''
})

const submit = async () => {
    processing.value = true

    try {
        let response = await axios.post(route('app.settings.points.check.check'), {
            ...form.value
        })
        response = response.data;
        console.log('response', response)

        if (response.success) {
            output.value = response.output
        }

        if (response.pesel) {
            update_form.value.pesel = response.pesel;
            update_form.value.points = response.points;
            update_form.value.comment = response.comment;
        }

    } catch (error) {
        // console.log(error)
        output.value = error
        processing.value = false
    }

    processing.value = false
}

const update_form = ref({
    pesel: '',
    points: '',
    comment: '',
})

const update = async () => {
    processing.value = true

    try {
        let response = await axios.post(route('app.settings.points.check.update'), {
            ...update_form.value
        })
        response = response.data;
        console.log('response', response)

        alert(response.msg)

        if (response.success) {
            update_form.value.pesel = '';
            update_form.value.points = '';
            update_form.value.comment = '';

            form.value.pesel = '';
            form.value.month = '';
            form.value.year = '';

            output.value = '';
        }

    } catch (error) {
        output.value = error
        processing.value = false
    }

    processing.value = false
}

</script>

<template>

    <Head :title="`VeritasApp - synchronizacja poziomu`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="">
            <v-card title="Sprawdź punkty użytkownika">
                <v-card-text>
                    <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center">
                        <v-text-field v-model="form.pesel" variant="outlined" placeholder="PESEL"></v-text-field>
                        <v-text-field v-model="form.month" variant="outlined"
                            placeholder="Miesiąć startowy, np. 02"></v-text-field>
                        <v-text-field v-model="form.year" variant="outlined"
                            placeholder="Rok startowy, np. 2025"></v-text-field>
                    </div>
                    <div class="tw-flex tw-flex-row">
                        <div>
                            <v-btn color="#16a34a" @click="submit()" :disabled="processing">Sprawdź</v-btn>
                        </div>
                    </div>
                    <div class="tw-mt-8 tw-text-xl" v-if="output" v-html="output"></div>
                    <div v-if="update_form.pesel" class="tw-mt-4">
                        <v-btn @click="update()">Aktualizuj punkty</v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </div>
    </AdminLayout>
</template>