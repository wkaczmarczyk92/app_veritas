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
const form = ref({...family_recommendation});
const button_value = ref('Poleć rodzinę');

const reset_values = () => {
    errors.value = {};
    form.value = {...family_recommendation};
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
    <section class="bg-gradient-to-br from-indigo-100 to-purple-200 overflow-hidden shadow-xl rounded-lg p-6">
        <h2 class="text-xl sm:text-2xl text-gray-800 font-bold">
            <div class="flex flex-row justify-between">
                <span>Poleć rodzinę</span>
                <i class="fa-sharp fa-regular fa-users mr-2"></i>
            </div>
        </h2>     
        <hr class="my-6">   
        <div class="text-gray-800">
            Z naszą firmą zarabiasz nie tylko jako opiekunka. Poleć rodzinę, która podejmie z nami współpracę i zyskaj <span class="text-blue-600 font-bold">{{ bonus.family_recommendation }}€</span>.
            Szczegóły i regulamin znajdziesz na stronie <a href="https://www.veritas-opieka.pl/formularz-polecen-rodzin//" target="_blank" class="text-blue-600 font-bold hover:cursor-pointer hover:text-blue-800 underline">veritas-opieka.pl</a>.<br>
            Możesz sprawdzić swoja aktualne polecenia rodzin <a href="#" class="underline text-blue-600" @click.prevent="$emit('update:model_value', true)">TUTAJ</a>.
        </div>
        <div class="my-8">
            <InputLabel value="Nazwisko rodziny" :text_color="'text-gray-800'"></InputLabel>
            <TextInput type="text" v-model="form.family_last_name" class="mt-1 block w-full mb-3" placeholder="Nazwisko rodziny..."></TextInput>
            <InputError class="mt-2" :message="errors.family_last_name ? errors.family_last_name[0] : ''" />

            <InputLabel value="Numer telefonu" :text_color="'text-gray-800'" :class="'mt-4'"></InputLabel>
            <div class="flex w-full">
                <select v-model="form.country_code" class="w-[100px] border border-r-0 border-gray-300 rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-1/4">
                    <option value="49">+49</option>
                    <option value="48">+48</option>
                </select>
                <input v-model="form.phone_number" type="text" class="border border-gray-300 rounded-r px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full" placeholder="Wpisz numer telefonu..." />
            </div>
            <InputError class="mt-2" :message="errors.phone_number ? errors.phone_number[0] : ''" />


            <InputLabel value="Dodatkowe informacje" :class="'mt-4'" :text_color="'text-gray-800'"></InputLabel>
            <TextareaInput v-model="form.info" :class="'mt-2'" placeholder="Dodatkowe informacje..." :rows="4"></TextareaInput>
            <InputError class="mt-2" :message="errors.info ? errors.info[0] : ''" />

            <div class="mt-4 flex flex-col">
                <div class="flex flex-row">
                    <Checkbox v-model:checked="form.processing_personal_data" id="processing_personal_data"></Checkbox>
                    <InputLabel for="processing_personal_data" value="Oświadczam, że posiadam zgodę polecanej osoby/rodziny na przekazanie jej danych osobowych." :text_color="'text-gray-800'"></InputLabel>
                </div>
                <InputError class="mt-2" :message="errors.processing_personal_data ? errors.processing_personal_data[0] : ''" />
            </div>

        </div>
        <div class="flex items-center mt-4">
            <MButton
                add_class="border border-green-700"
                :disabled="disabled"
                :value="button_value"
                @click="submit()"
                icon="fa-solid fa-users-medical"
                color="text-green-700"
                bg="bg-transparent"
                hover="hover:bg-green-700 hover:text-white"
            ></MButton>
        </div>
    </section>
</template>