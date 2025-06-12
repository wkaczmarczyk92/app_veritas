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

import { useUserStore } from '@/Pinia/UserStore';
import { useModalStore } from '@/Pinia/ModalStore';

import TInput from '@/Composables/Form/TInput.vue';

import Button from '@/Composables/Buttons/Button.vue';

const userStore = useUserStore();
const modalStore = useModalStore();

await userStore.set_user();

// const props = defineProps({
//     ready_to_departure_dates: {
//         type: [Object, null]
//     }
// });


// const emit = defineEmits([
//     'close',
//     'update'
// ]);

const disabled = ref(false);
const useAlertStore = AlertStore();

const departure_date = ref(userStore.user.ready_to_departure_dates?.departure_date ?? '');
const errors = ref({});

console.log(departure_date.value)

const on = () => {
    disabled.value = true;
    errors.value = {};
}

const off = () => {
    disabled.value = false;
}

async function submit() {
    on()
    departure_date.value = format(departure_date.value);
    let response = await axios.post(route('readytodeparturedate.store.or.update'), {
        departure_date: departure_date.value
    });

    response = response.data;

    if (response?.errors) {
        errors.value = response.errors;
        off()
        return;
    }

    if (response?.ready_to_departure) {
        userStore.user.ready_to_departure_dates = response?.ready_to_departure
    }

    useAlertStore.pushAlert(response.alert_type, response.msg);
    off()
}

async function removeReadyToDepartureDate() {
    on()
    let response = await axios.patch(route('readytodeparturedate.destroy'), {
        id: userStore.user.ready_to_departure_dates.id
    });

    if (response.data.success) {
        userStore.user.ready_to_departure_dates = null;
        departure_date.value = null;
    }

    useAlertStore.pushAlert(response.data.alert_type, response.data.msg);
    off()
}

</script>

<template>
    <h2 class="tw-text-2xl tw-font-bold tw-mt-3 tw-mb-6 tw-text-gray-800">Zgłoś chęć wyjazdu!</h2>
    <p>
        <!-- <InputLabel class="tw-mb-2" value="Wybierz datę dyspozycyjności"></InputLabel> -->
        <VueDatePicker v-model="departure_date" :format="format" :enable-time-picker="false" auto-apply>
            <template #trigger>
                <TInput label="Wybierz datę dyspozycyjności" v-model:model_value="departure_date" :callback="format"
                    :error="errors?.departure_date ? errors.departure_date[0] : ''" />

            </template>
        </VueDatePicker>
        <!-- <InputError class="tw-mt-2" :message="errors?.departure_date ? errors.departure_date[0] : ''" /> -->
    </p>
    <p class="tw-mt-2 tw-text-red-500 tw-underline hover:tw-cursor-pointer"
        v-if="userStore.user.ready_to_departure_dates" @click="removeReadyToDepartureDate">
        Usuń datę gotowości do wyjazdu
    </p>
    <div class="tw-mt-6 tw-text-right tw-flex tw-flex-col sm:tw-flex-row tw-justify-end tw-gap-1">
        <Button :value="disabled ? 'Aktualizowanie...' : 'Aktualizuj datę'" :disabled="disabled" @click="submit()">
            <template v-slot:icon>
                <i class="fa-solid fa-paper-plane-top tw-text-2xl"></i>
            </template>
        </Button>
        <Button value="Zamknij" :disabled="disabled" id="closeModal"
            @click="modalStore.visibility.ready_to_departure = false" color="red">
            <template v-slot:icon>
                <i class="fa-solid fa-xmark tw-text-2xl"></i>
            </template>
        </Button>
        <!-- <SButton :disabled="disabled" value="Zgłoś problem" @click="submit()">
        </SButton>
        <PrimaryButton id="closeModal" @click="modalStore.visibility.bok = false">
            Zamknij
        </PrimaryButton> -->
    </div>
    <!-- <div class="tw-mt-6 tw-text-right">
        <SButton :disabled="disabled" @click="submit()" value="Aktualizuj datę">
        </SButton>
        <PrimaryButton id="closeModal" @click="modalStore.visibility.ready_to_departure = false">
            Zamknij
        </PrimaryButton>
    </div> -->
</template>
