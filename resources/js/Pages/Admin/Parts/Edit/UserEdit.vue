<script setup>

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

// import { useForm } from '@inertiajs/vue3';


import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { format } from '@/Components/Functions/DateFormat.vue';
import SButton from '@/Components/SButton.vue';

import AlertDanger from '@/Components/Functions/AlertDanger.vue';

import { ref } from 'vue';
import axios from 'axios';
import { AlertStore } from '@/Pinia/AlertStore';


const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits([
    'update:user',
    'edit',
]);

const useAlertStore = AlertStore();

const user_data = ref({ ...props.user })
const errors = ref({});
const disabled = ref(false);
user_data.value.ready_to_departure_dates = user_data.value.ready_to_departure_dates == null ? { departure_date: null } : { departure_date: user_data.value?.ready_to_departure_dates.departure_date };

const submit = () => {
    errors.value = {};
    user_data.value.ready_to_departure_dates.departure_date = format(user_data.value.ready_to_departure_dates.departure_date);
    disabled.value = true;
    axios.patch(route('user.update'), {
        ...user_data.value,
    }).then(response => {
        if (!response.data.success) {
            if (response.data?.errors) {
                errors.value = response.data.errors;
            }

            if (response.data?.msg) {
                useAlertStore.pushAlert('danger', response.data.msg);
            }
        }

        if (response.data.success) {
            emit('update:user', user_data.value);
            emit('edit');
            useAlertStore.pushAlert('success', 'Dane użytkownika zostały zaktualizowane.');
        }

        disabled.value = false;
    })
}

</script>
<template>
    <v-card class="tw-shadow-xl tw-rounded tw-p-10">
        <template v-slot:title>
            <div class="tw-flex tw-flex-row tw-justify-between tw-items-center">
                <div class="tw-flex tw-flex-row tw-items-center tw-gap-2">
                    <div>Edycja</div>
                </div>
            </div>
        </template>
        <v-card-text>
            <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-6">
                <v-text-field variant="outlined" v-model="user_data.pesel" label="Pesel"
                    :error-messages="errors?.pesel"></v-text-field>
                <v-text-field variant="outlined" v-model="user_data.user_profiles.first_name" label="Imię"
                    :error-messages="errors['user_profiles.first_name']"></v-text-field>
                <v-text-field variant="outlined" v-model="user_data.user_profiles.last_name" label="Nazwisko"
                    :error-messages="errors['user_profiles.last_name']"></v-text-field>
                <v-text-field variant="outlined" v-model="user_data.user_profiles.email" label="Adres e-mail"
                    :error-messages="errors['user_profiles.email']"></v-text-field>
                <v-text-field variant="outlined" v-model="user_data.user_profiles.phone_number" label="Numer telefonu"
                    :error-messages="errors['user_profiles.phone_number']"></v-text-field>
            </div>

            <h2 class="tw-font-semibold tw-text-xl tw-leading-tight tw-mt-14">
                Dodatkowe informacje
            </h2>
            <div class="tw-flex tw-flex-col tw-mt-6">
                <div class="tw-grow">Edytuj datę gotowości do wyjazdu</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <VueDatePicker class="tw-right-dp tw-mt-2 sm:tw-mt-0"
                        v-model="user_data.ready_to_departure_dates.departure_date" :format="format" :teleport="true"
                        :enable-time-picker="false" auto-apply>
                    </VueDatePicker>

                    <InputError class="tw-mb-2"
                        :message="errors['ready_to_departure_dates.departure_date'] ? errors['ready_to_departure_dates.departure_date'][0] : ''" />
                </div>
            </div>
        </v-card-text>
        <v-card-actions>
            <div class="tw-flex tw-w-full tw-gap-4 tw-justify-center tw-items-center">
                <v-btn :disabled="disabled" @click="submit()" variant="flat" color="#16a34a">
                    Aktualizuj
                </v-btn>
                <v-btn :disabled="disabled" @click="$emit('edit')" variant="flat" color="#dc2626">
                    Anuluj
                </v-btn>
            </div>
        </v-card-actions>
    </v-card>
</template>
