<script setup>

import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import axios from 'axios';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';

import SButton from '@/Components/SButton.vue';

import { AlertStore } from '@/Pinia/AlertStore';
import { useModalStore } from '@/Pinia/ModalStore';

import TInput from '@/Composables/Form/TInput.vue';

import Button from '@/Composables/Buttons/Button.vue';
import Textarea from '@/Composables/Form/TextArea.vue';

const modalStore = useModalStore();

const init_form = {
    subject: '',
    msg: ''
};

const form = ref({ ...init_form });
const errors = ref({});
const useAlertStore = AlertStore();
const disabled = ref(false);

const reset_values = () => {
    form.value = { ...init_form };
    errors.value = {};
}

const submit = async () => {
    errors.value = {}
    disabled.value = true;

    let response = await axios.post(route('contactform.store'), form.value)

    console.log(response)

    response = response.data

    if (response.errors) {
        errors.value = response.errors;
    }

    if (response.success == true) {
        useAlertStore.pushAlert('success', 'Twoja wiadomość została wysłana.');
        reset_values();

        console.log(form.value)
    }

    disabled.value = false;
}


</script>

<template>
    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-8">Skontaktuj się z nami!</h2>
    <h3 class="tw-text-lg tw-mb-3 tw-text-gray-800 tw-mb-4">Formularz kontaktowy</h3>
    <div>
        <TInput class="tw-mb-4" label="Temat wiadomości (opcjonalnie)" v-model:model_value="form.subject" clearable
            :error="errors.subject ? errors.subject[0] : ''" />

        <Textarea v-model:model_value="form.msg" label="Wpisz tekst wiadomości..." clearable
            :error="errors.msg ? errors.msg[0] : ''" />
    </div>
    <div class="tw-mt-6 tw-text-right tw-flex tw-flex-row tw-justify-end tw-gap-1">

        <Button :value="disabled ? 'Wysyłanie wiadomości...' : 'Wyślij wiadomość'" :disabled="disabled"
            @click="submit()">
            <template v-slot:icon>
                <i class="fa-solid fa-paper-plane-top tw-text-2xl"></i>
            </template>
        </Button>
        <Button value="Zamknij" :disabled="disabled" id="closeModal" @click="modalStore.visibility.contact_form = false"
            color="red">
            <template v-slot:icon>
                <i class="fa-solid fa-xmark tw-text-2xl"></i>
            </template>
        </Button>
    </div>
</template>
