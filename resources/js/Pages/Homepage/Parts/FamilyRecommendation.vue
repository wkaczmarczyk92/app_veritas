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

    // console.log(form.value);
    // return;

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
    <section
        class="tw-p-6 tw-overflow-hidden tw-rounded-lg tw-shadow-xl tw-bg-gradient-to-br tw-from-indigo-100 tw-to-purple-200">
        <h2 class="tw-text-xl tw-font-bold tw-text-gray-800 sm:tw-text-2xl">
            <div class="tw-flex tw-flex-row tw-justify-between">
                <span>Poleć rodzinę</span>
                <i class="tw-mr-2 fa-sharp fa-regular fa-users"></i>
            </div>
        </h2>
        <hr class="tw-my-6">
        <div class="tw-text-gray-800">
            Z naszą firmą zarabiasz nie tylko jako opiekunka. Poleć rodzinę, która podejmie z nami współpracę i zyskaj <span
                class="tw-font-bold tw-text-blue-600">{{ bonus.family_recommendation }}€</span>.
            Szczegóły i regulamin znajdziesz na stronie <a href="https://www.veritas-opieka.pl/formularz-polecen-rodzin//"
                target="_blank"
                class="tw-font-bold tw-text-blue-600 tw-underline hover:tw-cursor-pointer hover:tw-text-blue-800">veritas-opieka.pl</a>.<br>
            Możesz sprawdzić swoja aktualne polecenia rodzin <a href="#" class="tw-text-blue-600 tw-underline"
                @click.prevent="$emit('update:model_value', true)">TUTAJ</a>.
        </div>
        <div class="tw-my-8">
            <InputLabel value="Nazwisko rodziny" :text_color="'tw-text-gray-800'"></InputLabel>
            <TextInput type="text" v-model="form.family_last_name" class="tw-block tw-w-full tw-mt-1 tw-mb-3"
                placeholder="Nazwisko rodziny..."></TextInput>
            <InputError class="tw-mt-2" :message="errors.family_last_name ? errors.family_last_name[0] : ''" />

            <InputLabel value="Numer telefonu" :text_color="'tw-text-gray-800'" :class="'tw-mt-4'"></InputLabel>
            <div class="tw-flex tw-w-full">
                <select v-model="form.country_code"
                    class="tw-w-[100px] tw-border tw-border-r-0 tw-border-gray-300 tw-rounded-l tw-bg-gray-100 tw-px-4 tw-py-2 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-">
                    <option value="49">+49</option>
                    <option value="48">+48</option>
                </select>
                <input v-model="form.phone_number" type="text"
                    class="tw-w-full tw-px-4 tw-py-2 tw-border tw-border-gray-300 tw-rounded-r focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500"
                    placeholder="Wpisz numer telefonu..." />
            </div>
            <InputError class="tw-mt-2" :message="errors.phone_number ? errors.phone_number[0] : ''" />


            <InputLabel value="Dodatkowe informacje" :class="'tw-mt-4'" :text_color="'tw-text-gray-800'"></InputLabel>
            <TextareaInput v-model="form.info" :class="'tw-mt-2'" placeholder="Dodatkowe informacje..." :rows="4">
            </TextareaInput>
            <InputError class="tw-mt-2" :message="errors.info ? errors.info[0] : ''" />

            <div class="tw-flex tw-flex-col tw-mt-4">
                <div class="tw-flex tw-flex-row">
                    <Checkbox v-model:checked="form.processing_personal_data" id="processing_personal_data"></Checkbox>
                    <InputLabel for="processing_personal_data"
                        value="Oświadczam, że posiadam zgodę polecanej osoby/rodziny na przekazanie jej danych osobowych."
                        :text_color="'tw-text-gray-800'"></InputLabel>
                </div>
                <InputError class="tw-mt-2"
                    :message="errors.processing_personal_data ? errors.processing_personal_data[0] : ''" />
            </div>

        </div>
        <div class="tw-flex tw-items-center tw-mt-4">
            <MButton add_class="tw-border tw-border-green-700" :disabled="disabled" :value="button_value" @click="submit()"
                icon="fa-solid fa-users-medical" color="tw-text-green-700" bg="tw-bg-transparent"
                hover="hover:tw-bg-green-700 hover:tw-text-white"></MButton>
        </div>
    </section>
</template>
