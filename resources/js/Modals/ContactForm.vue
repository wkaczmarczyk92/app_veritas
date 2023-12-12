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

const form = ref({...init_form});
const errors = ref({});
const useAlertStore = AlertStore();
const sbutton_value = ref('Wyślij wiadomość');
const disabled = ref(false);

const reset_values = () => {
    form.value = {...init_form};
    errors.value ={};
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
    <h2 class="text-2xl font-bold text-gray-800 mb-8">Skontaktuj się z nami!</h2>
    <h3 class="text-lg mb-3 text-gray-800">Formularz kontaktowy</h3>
    <div>
        <InputLabel value="Temat wiadomości (opcjonalnie)" :text_color="'text-gray-600'"></InputLabel>
        <TextInput type="text" v-model="form.subject" class="mt-1 block w-full mb-3" placeholder="Temat wiadomości..."></TextInput>
        <InputError class="mt-2" :message="errors.subject ? errors.subject[0] : ''" />
        
        <InputLabel value="Wpisz tekst wiadomości..." :class="'mt-4'" :text_color="'text-gray-600'"></InputLabel>
        <TextareaInput v-model="form.msg" :class="'mt-2'" placeholder="Treść wiadomości..."></TextareaInput>
        <InputError class="mt-2" :message="errors.msg ? errors.msg[0] : ''" />
    </div>
    <div class="mt-6 text-right flex flex-row justify-end gap-1">
        <SButton class="ml-4" :value="sbutton_value" :disabled="disabled" @click="submit()"></SButton>
        <PrimaryButton id="closeModal" @click="$emit('close')">
            Zamknij
        </PrimaryButton>
    </div>
</template>