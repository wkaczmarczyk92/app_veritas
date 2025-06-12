<script setup>

import axios from 'axios';
import { useForm } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import SelectInput from '@/Components/SelectInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import SButton from '@/Components/SButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { useModalStore } from '@/Pinia/ModalStore';

import TInput from '@/Composables/Form/TInput.vue';
import Textarea from '@/Composables/Form/Textarea.vue';
import Select from '@/Composables/Form/Select.vue';

import Button from '@/Composables/Buttons/Button.vue';

import Loader from '@/Components/Loader.vue';

async function getSubjects() {
    let subjects = await axios.get(route('boksubject.index'));
    return subjects.data;
}

const subjects = await getSubjects();
const useAlertStore = AlertStore();



const form_init = () => {
    return {
        subject_id: '',
        msg: '',
        bank_account: {
            owner_name: '',
            account_type_id: '',
            account_number: '',
            bank_name: '',
            swift: ''
        }
    }
}

const form = ref(form_init())

const modalStore = useModalStore();

const errors = ref({});
const disabled = ref(false);

const loader_state = ref(false)
const account_types = ref(null)



watch(
    () => form.value.subject_id,
    async (new_value) => {
        if (new_value == 8 && account_types.value == null) {
            loader_state.value = true
            let response = await axios.get(route('account.types.index'))
            account_types.value = response.data

            loader_state.value = false
        }
    }
)

const submit = async () => {
    disabled.value = true;
    errors.value = {};

    let response = await axios.post(route('bokrequest.store'), { ...form.value });
    response = response.data;
    // console.log(response);

    console.log('errors', response.errors)

    if (!response.success) {
        if (response?.errors) {
            errors.value = response.errors;
            disabled.value = false;
            return;
        }
    }

    let alert_type = response.success ? 'success' : 'danger';
    useAlertStore.pushAlert(alert_type, response.msg);

    if (response.success) {
        // form.value.subject_id = '';
        // form.value.msg = '';
        form.value = form_init()
    }

    disabled.value = false;
}

</script>


<template>
    <h2 class="tw-text-2xl tw-font-bold tw-text-gray-800 tw-mb-4">Zgłoszenia do Biura Obsługi Klienta</h2>

    <div class="modal-body">
        <div class="tw-flex tw-flex-col tw-mt-10">

            <v-select :items="subjects" item-title="subject" item-value="id" label="Widoczność testu" hide-details
                variant="outlined" v-model="form.subject_id" />
            <InputError :message="errors.subject_id ? errors.subject_id[0] : ''" class="tw-mt-1" />

            <div v-if="form.subject_id == 8" class="tw-my-6">
                <h2 class="tw-text-lg tw-font-bold tw-mb-4">Dane dane potrzebne do zmiany konta</h2>
                <Loader class="tw-grow" v-if="loader_state"></Loader>
                <div v-else class="tw-flex tw-flex-col tw-gap-4">
                    <TInput label="Właściciel konta" v-model:model_value="form.bank_account.owner_name" clearable
                        :error="errors['bank_account.owner_name'] ? errors['bank_account.owner_name'][0] : ''" />
                    <TInput label="Nazwa banku" v-model:model_value="form.bank_account.bank_name" clearable
                        :error="errors['bank_account.bank_name'] ? errors['bank_account.bank_name'][0] : ''" />

                    <Select :items="account_types" item-title="type_pl" item-value="id" label="Typ konta"
                        variant="outlined" v-model="form.bank_account.account_type_id"
                        :error="errors['bank_account.account_type_id'] ? errors['bank_account.account_type_id'][0] : ''" />

                    <TInput label="Numer konta" v-model:model_value="form.bank_account.account_number" clearable
                        :error="errors['bank_account.account_number'] ? errors['bank_account.account_number'][0] : ''" />

                    <TInput label="SWIFT" v-model:model_value="form.bank_account.swift" clearable
                        :error="errors['bank_account.swift'] ? errors['bank_account.swift'][0] : ''"
                        :disabled="form.bank_account.account_type_id == 2 || form.bank_account.account_type_id == null || form.bank_account.account_type_id == ''" />
                </div>
            </div>


            <Textarea label="Opisz problem" v-model:model_value="form.msg" class="tw-mt-4" clearable
                :error="errors.msg ? errors.msg[0] : ''" />
        </div>
    </div>

    <div class="tw-mt-6 tw-text-right tw-flex tw-flex-col sm:tw-flex-row tw-justify-end tw-gap-1">
        <Button :value="disabled ? 'Zgłaszanie...' : 'Zgłoś problem'" :disabled="disabled" @click="submit()">
            <template v-slot:icon>
                <i class="fa-solid fa-paper-plane-top tw-text-2xl"></i>
            </template>
        </Button>
        <Button value="Zamknij" :disabled="disabled" id="closeModal" @click="modalStore.visibility.bok = false"
            color="red">
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
</template>
