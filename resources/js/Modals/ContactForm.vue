<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import axios from 'axios';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';

import SButton from '@/Components/SButton.vue';

import SuccessAlert from '@/Components/Alerts/SuccessAlert.vue';
import { AlertStore } from '@/Pinia/AlertStore';

const emit = defineEmits([
    'close'
]);

const init_form = {
    subject: '',
    msg: ''
};

const form = ref({ ...init_form });
const errors = ref({});
const useAlertStore = AlertStore();
const sbutton_value = ref('Wyślij wiadomość');
const disabled = ref(false);

const reset_values = () => {
    form.value = { ...init_form };
    errors.value = {};
}

const submit = () => {
    sbutton_value.value = 'Wysyłanie...';
    disabled.value = true;

    axios.post(route('contactform.store'), form.value)
        .then(response => {
            if (response.data?.errors) {
                errors.value = response.data.errors;
            }

            if (response.data?.success == true) {
                useAlertStore.pushAlert('success', 'Twoja wiadomość została wysłana.');
                reset_values();
            }

            sbutton_value.value = 'Wyślij wiadomość';
            disabled.value = false;
        });
}


</script>

<template>
    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-8">Skontaktuj się z nami!</h2>
    <h3 class="tw-text-lg tw-mb-3 tw-text-gray-800">Formularz kontaktowy</h3>
    <div>
        <InputLabel value="Temat wiadomości (opcjonalnie)" :text_color="'tw-text-gray-600'"></InputLabel>
        <TextInput type="text" v-model="form.subject" class="tw-mt-1 tw-block tw-w-full tw-mb-3"
            placeholder="Temat wiadomości...">
        </TextInput>
        <InputError class="tw-mt-2" :message="errors.subject ? errors.subject[0] : ''" />

        <InputLabel value="Wpisz tekst wiadomości..." :class="'tw-mt-4'" :text_color="'tw-text-gray-600'"></InputLabel>
        <TextareaInput v-model="form.msg" :class="'tw-mt-2'" placeholder="Treść wiadomości..."></TextareaInput>
        <InputError class="tw-mt-2" :message="errors.msg ? errors.msg[0] : ''" />
    </div>
    <div class="tw-mt-6 tw-text-right tw-flex tw-flex-row tw-justify-end tw-gap-1">
        <SButton class="tw-ml-4" :value="sbutton_value" :disabled="disabled" @click="submit()"></SButton>
        <PrimaryButton id="closeModal" @click="$emit('close')">
            Zamknij
        </PrimaryButton>
    </div>
</template>
