<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Templates/Header.vue';
import { toRef, ref, watch } from 'vue';
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';


const props = defineProps({
    bonus: {
        type: Object,
        required: true
    }
});

const bonus_ref = toRef(props.bonus);
console.log(bonus_ref);

const form = ref({
    caretaker_bonus: props.bonus.caretaker_recommendation.bonus_value,
    family_bonus: props.bonus.family_recommendation.bonus_value
});


const errors = ref();
const useAlertStore = AlertStore();

const submit = async () => {
    errors.value = {};
    let response = await axios.patch(route('bonus.update'), { ...form.value });

    if (response.data?.errors) {
        errors.value = response.data.errors;
    }

    if (response.data?.alert_type) {
        useAlertStore.pushAlert(response.data.alert_type, response.data.msg);
    }

    if (response.data.success) {
        bonus_ref.value = response.data.bonus;
    }
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Ustawienia bonusów',
        disabled: true
    }
]

</script>


<template>
    <Head :title="`VeritasApp - ustawienia bonusów za polecenia`" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-max-w-5xl tw-px-2 tw-mx-auto sm:tw-px-6 lg:tw-px-16">
                <div class="tw-p-10 tw-bg-gray-100 tw-rounded tw-shadow-xl">
                    <Header h="4" add_class="tw-font-bold tw-mb-8" value="Aktualne ustawienia bonusów"></Header>

                    <div class="tw-flex tw-flex-col tw-justify-between tw-text-gray-800 sm:tw-flex-row">
                        <div>
                            Bonus za polecenie opiekunki
                        </div>
                        <div class="tw-text-xl tw-font-bold">
                            {{ bonus_ref.caretaker_recommendation.bonus_value }} €
                        </div>
                    </div>
                    <div class="tw-flex tw-flex-col tw-justify-between tw-mt-1 sm:tw-flex-row">
                        <div>
                            Bonus za polecenie rodziny
                        </div>
                        <div class="tw-text-xl tw-font-bold">
                            {{ bonus_ref.family_recommendation.bonus_value }} €
                        </div>
                    </div>


                    <hr class="tw-my-8">

                    <Header h="4" add_class="tw-font-bold tw-mb-8" value="Zmień ustawienia bonusów"></Header>

                    <InputLabel value="Bonus za polecenie opiekunki" />
                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1" v-model="form.caretaker_bonus" />
                    <InputError class="tw-mt-2"
                        :message="errors && errors.caretaker_bonus ? errors.caretaker_bonus[0] : ''" />

                    <InputLabel value="Bonus za polecenie rodziny" class="tw-mt-4" />
                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1" v-model="form.family_bonus" />
                    <InputError class="tw-mt-2" :message="errors && errors.family_bonus ? errors.family_bonus[0] : ''" />


                    <div class="tw-flex tw-flex-row tw-justify-center tw-mt-4">
                        <MButton @click="submit()" value="Aktualizuj" bg="tw-bg-gray-800" hover="hover:tw-bg-gray-600">
                        </MButton>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>
