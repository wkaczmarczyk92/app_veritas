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

const user_data = ref({...props.user})
const errors = ref({});
const disabled = ref(false);
user_data.value.ready_to_departure_dates = user_data.value.ready_to_departure_dates == null ? {departure_date: null} : {departure_date: user_data.value?.ready_to_departure_dates.departure_date};

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
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <div class="flex flex-col sm:flex-row justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                <i class="fa-solid fa-circle-user text-main"></i> 
                Edytuj dane użytkownika
            </h2>
            <p class="text-red-600 font-bold underline hover:text-red-900 hover:cursor-pointer" @click="$emit('toggle-user')">Zakończ edycję</p>
        </div>
        <div class="flex flex-col sm:flex-row mt-6">
            <div class="grow">PESEL</div>
            <div class="grow text-left sm:text-right">
                <TextInput
                    placeholder="Numer PESEL..."
                    class="text-left sm:text-right py-1 w-full sm:w-auto"
                    type="text"
                    v-model="user_data.pesel"
                >
                </TextInput>    
                <InputError class="mb-2" :message="errors.pesel ? errors.pesel[0] : ''" />
            </div>
        </div>
        <div class="flex flex-col sm:flex-row mt-3 sm:mt-1">
            <div class="grow">Imię</div>
            <div class="grow text-left sm:text-right">
                <TextInput
                    placeholder="Imię..."
                    class="text-left sm:text-right py-1 mt-1 w-full sm:w-auto"
                    type="text"
                    v-model="user_data.user_profiles.first_name"
                >
                </TextInput>    
                <InputError class="mb-2" :message="errors['user_profiles.first_name'] ? errors['user_profiles.first_name'][0] : ''" />
            </div>
        </div>
        <div class="flex flex-col sm:flex-row mt-3 sm:mt-1">
            <div class="grow">Nazwisko</div>
            <div class="grow text-left sm:text-right">
                <TextInput                
                    placeholder="Nazwisko..."
                    class="text-left sm:text-right py-1 mt-1 w-full sm:w-auto"
                    type="text"
                    v-model="user_data.user_profiles.last_name"
                >
                </TextInput>    
                <InputError class="mb-2" :message="errors['user_profiles.last_name'] ? errors['user_profiles.last_name'][0] : ''" />
            </div>
        </div>
        <div class="flex flex-col sm:flex-row mt-3 sm:mt-1">
            <div class="grow">E-mail</div>
            <div class="grow text-left sm:text-right">
                <TextInput
                    placeholder="Adres e-mail..."
                    class="text-left sm:text-right py-1 mt-1 w-full sm:w-auto"
                    type="text"
                    v-model="user_data.user_profiles.email"
                >
                </TextInput>    
                <InputError class="mb-2" :message="errors['user_profiles.email'] ? errors['user_profiles.email'][0] : ''" />
            </div>
        </div>
        <div class="flex flex-col sm:flex-row mt-3 sm:mt-1">
            <div class="grow">Numer telefonu</div>
            <div class="grow text-left sm:text-right">
                <TextInput
                    placeholder="Numer telefonu..."
                    class="text-left sm:text-right py-1 mt-1 w-full sm:w-auto"
                    type="text"
                    v-model="user_data.user_profiles.phone_number"
                >
                </TextInput>    
                
                <InputError class="mb-2" :message="errors['user_profiles.phone_number'] ? errors['user_profiles.phone_number'][0] : ''" />
            </div>
        </div>

        <h2 class="font-semibold text-xl leading-tight mt-14"><i class="fa-sharp fa-solid fa-calendar-circle-user"></i>
            Dodatkowe informacje</h2>
        <div class="flex flex-col sm:flex-row mt-6">
            <div class="grow">Edytuj datę gotowości do wyjazdu</div>
            <div class="grow text-left sm:text-right">
                <VueDatePicker 
                    class="right-dp mt-2 sm:mt-0"
                    v-model="user_data.ready_to_departure_dates.departure_date" 
                    :format="format" 
                    :enable-time-picker="false"
                    auto-apply
                >
                </VueDatePicker>
                
                <InputError class="mb-2" :message="errors['ready_to_departure_dates.departure_date'] ? errors['ready_to_departure_dates.departure_date'][0] : ''" />
            </div>
        </div>
        <div class="w-full">
            
            <div class="text-right">
                <SButton
                    :disabled="disabled"
                    class="mt-4"
                    value="Aktualizuj dane"
                    @click="submit"
                ></SButton>
            </div>
        </div>
    </div>
</template>