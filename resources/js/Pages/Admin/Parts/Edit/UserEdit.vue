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
    'toggle-user',
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
            emit('toggle-user');
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
                    <i class="fa-solid fa-circle-user text-main"></i>
                    <div> Edytuj dane użytkownika</div>
                </div>
                <p class="tw-text-red-600 tw-font-bold tw-underline hover:tw-text-red-900 hover:tw-cursor-pointer tw-text-sm"
                    @click="$emit('toggle-user')">Zakończ edycję</p>
            </div>
        </template>
        <v-card-text>

            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-6">
                <div class="tw-grow">PESEL</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <TextInput placeholder="Numer PESEL..."
                        class="tw-text-left sm:tw-text-right tw-py-1 tw-w-full sm:tw-w-auto" type="tw-text"
                        v-model="user_data.pesel">
                    </TextInput>
                    <InputError class="tw-mb-2" :message="errors.pesel ? errors.pesel[0] : ''" />
                </div>
            </div>
            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-3 sm:tw-mt-1">
                <div class="tw-grow">Imię</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <TextInput placeholder="Imię..."
                        class="tw-text-left sm:tw-text-right tw-py-1 tw-mt-1 tw-w-full sm:tw-w-auto" type="tw-text"
                        v-model="user_data.user_profiles.first_name">
                    </TextInput>
                    <InputError class="tw-mb-2"
                        :message="errors['user_profiles.first_name'] ? errors['user_profiles.first_name'][0] : ''" />
                </div>
            </div>
            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-3 sm:tw-mt-1">
                <div class="tw-grow">Nazwisko</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <TextInput placeholder="Nazwisko..."
                        class="tw-text-left sm:tw-text-right tw-py-1 tw-mt-1 tw-w-full sm:tw-w-auto" type="text"
                        v-model="user_data.user_profiles.last_name">
                    </TextInput>
                    <InputError class="tw-mb-2"
                        :message="errors['user_profiles.last_name'] ? errors['user_profiles.last_name'][0] : ''" />
                </div>
            </div>
            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-3 sm:tw-mt-1">
                <div class="tw-grow">E-mail</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <TextInput placeholder="Adres e-mail..."
                        class="tw-text-left sm:tw-text-right tw-py-1 tw-mt-1 tw-w-full sm:tw-w-auto" type="text"
                        v-model="user_data.user_profiles.email">
                    </TextInput>
                    <InputError class="tw-mb-2"
                        :message="errors['user_profiles.email'] ? errors['user_profiles.email'][0] : ''" />
                </div>
            </div>
            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-3 sm:tw-mt-1">
                <div class="tw-grow">Numer telefonu</div>
                <div class="tw-grow tw-text-left sm:tw-text-right">
                    <TextInput placeholder="Numer telefonu..."
                        class="tw-text-left sm:tw-text-right tw-py-1 tw-mt-1 tw-w-full sm:tw-w-auto" type="text"
                        v-model="user_data.user_profiles.phone_number">
                    </TextInput>

                    <InputError class="tw-mb-2"
                        :message="errors['user_profiles.phone_number'] ? errors['user_profiles.phone_number'][0] : ''" />
                </div>
            </div>

            <h2 class="tw-font-semibold tw-text-xl tw-leading-tight tw-mt-14"><i
                    class="fa-sharp fa-solid fa-calendar-circle-user"></i>
                Dodatkowe informacje</h2>
            <div class="tw-flex tw-flex-col sm:tw-flex-row tw-mt-6">
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
            <v-btn :disabled="disabled" @click="submit()" variant="tonal" color="#16a34a">
                Aktualizuj dane
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
