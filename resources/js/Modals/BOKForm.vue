<script setup>

import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import SButton from '@/Components/SButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { ref } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { useModalStore } from '@/Pinia/ModalStore';

async function getSubjects() {
    let subjects = await axios.get(route('boksubject.index'));
    return subjects.data;
}

const subjects = await getSubjects();
const useAlertStore = AlertStore();
const form = ref({ subject_id: '', msg: '' })

const modalStore = useModalStore();

const errors = ref({});
const disabled = ref(false);

const submit = async () => {
    disabled.value = true;
    errors.value = {};

    let response = await axios.post(route('bokrequest.store'), { ...form.value });
    response = response.data;
    // console.log(response);

    if (!response.success) {
        if (response?.errors) {
            errors.value = response.errors;
            disabled.value = false;
            return;
        }
    }

    let alert_type = response.success ? 'success' : 'danger';
    useAlertStore.pushAlert(alert_type, response.msg);

    if (response.success) {
        form.value.subject_id = '';
        form.value.msg = '';
    }

    disabled.value = false;
}

</script>


<template>
    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-4">Zgłoszenia do Biura Obsługi Klienta</h2>

    <div class="modal-body">
        <div class="tw-flex tw-flex-col">
            <InputLabel value="Wybierz temat"></InputLabel>
            <SelectInput v-model="form.subject_id" :options="subjects" :name_string="'subject'"
                :class="'tw-mt-2 tw-border-solid'">
            </SelectInput>
            <InputError class="tw-mt-2" :message="errors.subject_id ? errors.subject_id[0] : ''" />

            <InputLabel value="Opisz problem" :class="'tw-mt-4'"></InputLabel>
            <TextareaInput v-model="form.msg" :class="'tw-mt-2'"></TextareaInput>
            <InputError class="tw-mt-2" :message="errors.msg ? errors.msg[0] : ''" />
        </div>
    </div>

    <div class="tw-mt-6 tw-text-right">
        <SButton :disabled="disabled" value="Zgłoś problem" @click="submit()">
        </SButton>
        <PrimaryButton id="closeModal" @click="modalStore.visibility.bok = false">
            Zamknij
        </PrimaryButton>
    </div>
</template>



