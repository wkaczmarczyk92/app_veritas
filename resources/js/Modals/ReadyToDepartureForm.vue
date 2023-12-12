<script setup>

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { format } from '@/Components/Functions/DateFormat.vue';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SButton from '@/Components/SButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';
import { AlertStore } from '@/Pinia/AlertStore';

const props = defineProps({
    ready_to_departure_dates: {
        type: [Object, null]
    }
});

const emit = defineEmits([
    'close',
    'update'
]);

const disabled = ref(false);
const useAlertStore = AlertStore();

const departure_date = ref(props.ready_to_departure_dates?.departure_date ?? '');
const errors = ref({});

async function submit() {
    disabled.value = true;
    errors.value = {};
    departure_date.value = format(departure_date.value);
    let response = await axios.post(route('readytodeparturedate.store.or.update'), {
        departure_date: departure_date.value
    });

    response = response.data;

    if (response?.errors) {
        errors.value = response.errors;
        disabled.value = false;    
        return;
    }

    if (response?.ready_to_departure) {
        emit('update', response.ready_to_departure);
    }

    useAlertStore.pushAlert(response.alert_type, response.msg);
    disabled.value = false;    
}

async function removeReadyToDepartureDate() {
    let response = await axios.patch(route('readytodeparturedate.destroy'), {
        id: props.ready_to_departure_dates.id
    });

    if (response.data.success) {
        emit('update', null);
        departure_date.value = null;
    }

    useAlertStore.pushAlert(response.data.alert_type, response.data.msg);
}

</script>

<template>
    <h2 class="text-2xl font-bold mt-3 mb-6 text-gray-800">Zgłoś chęć wyjazdu!</h2>
    <p>
        <InputLabel class="mb-2" value="Wybierz datę dyspozycyjności"></InputLabel>
        <VueDatePicker 
            v-model="departure_date" 
            :format="format" 
            :enable-time-picker="false"
            auto-apply
        >
        </VueDatePicker>
        <InputError class="mt-2" :message="errors?.departure_date ? errors.departure_date[0] : ''" />
    </p>
    <p class="mt-2 text-red-500 underline hover:cursor-pointer" v-if="ready_to_departure_dates" @click="removeReadyToDepartureDate">
        Usuń datę gotowości do wyjazdu
    </p>
    <div class="mt-6 text-right">
        <SButton 
            :disabled="disabled"
            @click="submit()" 
            value="Aktualizuj datę">
        </SButton>
        <PrimaryButton id="closeModal" @click="$emit('close')">
            Zamknij
        </PrimaryButton>
    </div>
</template>