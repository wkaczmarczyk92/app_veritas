<script setup>
import { ref } from 'vue';
import axios from 'axios';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import Checkbox from '@/Components/Checkbox.vue';

import { AlertStore } from '@/Pinia/AlertStore';
import { useModalStore } from '@/Pinia/ModalStore';

const modalStore = useModalStore();

defineProps({
    model_value: {
        type: Boolean,
        required: true
    },
    bonus: {
        type: Object
    }
})

defineEmits([
    'update:model_value',
]);

const family_recommendation = {
    family_last_name: '',
    country_code: '49',
    phone_number: '',
    info: '',
    processing_personal_data: false
};

const useAlertStore = AlertStore();
const errors = ref({});
const disabled = ref(false);
const form = ref({ ...family_recommendation });
const button_value = ref('Poleć rodzinę');

const reset_values = () => {
    errors.value = {};
    form.value = { ...family_recommendation };
}

const submit = () => {
    button_value.value = 'Polecanie rodziny w toku...';

    console.log(form.value);
    return;

    axios.post(route('familyrecommendation.store'), form.value)
        .then(response => {

            if (!response.data.success) {
                if (response.data?.errors) {
                    errors.value = response.data.errors;
                }

                if (response.data?.msg) {
                    useAlertStore.pushAlert('danger', response.data.msg);
                }
            }

            if (response.data?.success == true) {
                useAlertStore.pushAlert('success', 'Pomyślnie przesłano formularz polecenia rodziny. Dziękujemy!');
                reset_values();
            }

            button_value.value = 'Poleć rodzinę';
            disabled.value = false;
        });
}

</script>

<template>
    <v-card class="!tw-shadow-xl !tw-bg-gradient-to-br !tw-from-indigo-100 !tw-to-purple-200">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
                <div>
                    Poleć rodzinę
                </div>
                <i class="tw-mr-2 fa-sharp fa-regular fa-users"></i>
            </div>
        </template>
        <v-card-text>
            <div class="tw-text-gray-800">
                Z naszą firmą zarabiasz nie tylko jako opiekunka. Poleć rodzinę, która podejmie z nami współpracę i
                zyskaj
                <span class="tw-font-bold tw-text-blue-600">{{ bonus.family_recommendation }}€</span>.
                Szczegóły i regulamin znajdziesz na stronie <a
                    href="https://www.veritas-opieka.pl/formularz-polecen-rodzin//" target="_blank"
                    class="tw-font-bold tw-text-blue-600 tw-underline hover:tw-cursor-pointer hover:tw-text-blue-800">veritas-opieka.pl</a>.<br>
                Możesz sprawdzić swoja aktualne polecenia rodzin <a href="#" class="tw-text-blue-600 tw-underline"
                    @click.prevent="modalStore.visibility.family_recommendations = true">TUTAJ</a>.
            </div>
            <div class="tw-mt-10">
                <v-text-field v-model="form.family_last_name" label="Nazwisko rodziny..." variant="outlined"
                    :error-messages="errors.family_last_name ? errors.family_last_name[0] : ''" />

                <div class="tw-flex tw-flex-row tw-items-center tw-gap-1">
                    <v-select v-model="form.country_code" variant="outlined" class="tw-w-1/3" :items="[48, 49]"
                        label="Kierunkowy"></v-select>
                    <v-text-field class="tw-w-full" label="Wpisz numer telefonu..." variant="outlined" />
                </div>

                <v-textarea v-model="form.phone_number" variant="outlined" label="Dodatkowe informacje"></v-textarea>
                <v-checkbox v-model="form.processing_personal_data"
                    label="Oświadczam, że posiadam zgodę polecanej osoby/rodziny na przekazanie jej danych osobowych." />

                <v-btn variant="outlined" color="#16a34a" @click="submit()">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-users-medical"></i>
                    </template>
                    Poleć rodzinę
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>
