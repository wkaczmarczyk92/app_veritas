<script setup>

import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SButton from '@/Components/SButton.vue';
import { format } from '@/Components/Functions/DateFormat.vue'
import SelectInput from '@/Components/SelectInput.vue';
import Icon from '@/Components/Functions/Icon';
import { AlertStore } from '@/Pinia/AlertStore';
import axios from 'axios';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    language: {
        type: Object,
        required: true
    }
});

console.log(props.data);

const data = ref(props.data);
const errors = ref({});
const icon = ref(new Icon);
const useAlertStore = AlertStore();
const disabled = ref(false)

const submit = () => {
    errors.value = {};
    // disabled.value = true;
    axios.patch(route('caretaker.recommendation.update'), { ...data.value })
        .then(response => {
            console.log(response.data);
            disabled.value = false;
            if (response.data?.errors) {
                errors.value = response.data.errors;
                disabled.value = false;
                return;
            }

            let alert_type = response.data.alert_type;
            let msg = response.data.msg;

            useAlertStore.pushAlert(alert_type, msg);

            if (response.data?.data) {
                data.value = response.data.data;
            }

            // if (!response.data.success) {
            //     errors.value = response.data.errors;
            // }

            // if (response.data.success) {
            //     data.value = response.data.data;
            //     useAlertStore.pushAlert('success', 'Dane polecanej opiekunki zostały zapisane i wysłane do CC.');
            // }

            disabled.value = false;
        })
}


const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Polecenia opiekunek',
        disabled: false,
        href: route('caretaker.recommendations.index')
    },
    {
        title: 'Opiekunka #' + props.data.user.id,
        disabled: true,
    }
]

const delete_caretaker_recommendation = async () => {
    if (confirm('Na pewno chcesz usunąć polecenie opiekunki?')) {
        let response = await axios.delete(route('caretaker.recommendation.destroy', { id: props.data.id }));
        response = response.data;

        console.log(response);

        if (response.success) {
            alert(response.msg);;
            window.location.href = route('caretaker.recommendations.index');
        } else {
            useAlertStore.pushAlert(response.alert_type, response.msg);
        }
    }
}

</script>
<template>
    <Head title="VeritasApp - opiekunka" />
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
            <div class="tw-px-2 tw-mx-auto tw-max-w-7xl sm:tw-px-6 lg:tw-px-16">
                <div class="tw-bg-gray-100 tw-rounded shadow-xl">
                    <div class="tw-flex tw-flex-col tw-justify-between tw-gap-10 md:tw-flex-row">
                        <div class="tw-p-6 md:tw-w-1/3">
                            <h2 class="tw-text-2xl tw-font-bold">Dane osoby polecającej</h2>
                            <hr class="tw-my-8">
                            <div class="tw-flex tw-flex-row tw-justify-between tw-text-sm">
                                <div>Imię i nazwisko</div>
                                <div>
                                    {{ `${data.user.user_profiles.first_name} ${data.user.user_profiles.last_name}` }}
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2 tw-text-sm">
                                <div>Adres e-mail</div>
                                <div>
                                    {{ data.user.user_profiles.email }}
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2 tw-text-sm">
                                <div>Numer telefonu</div>
                                <div>
                                    {{ data.user.user_profiles.phone_number }}
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2 tw-text-sm">
                                <div>Przejdź do profilu</div>
                                <div>
                                    <a class="edit-user" :href="`/uzytkownik/${data.user.id}`">
                                        <i class="tw-text-lg fa-solid fa-user-pen"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-justify-between tw-mt-6 tw-text-sm">
                                <div>Utworzono</div>
                                <div>
                                    {{ format(data.created_at) }}
                                </div>
                            </div>

                            <h2 class="tw-mt-16 tw-text-2xl tw-font-bold">Dodatkowe informacje</h2>
                            <hr class="tw-my-8">
                            <div class="tw-flex tw-flex-row tw-justify-between tw-mt-2 tw-text-sm">
                                <div>Bonus gotowy do wypłaty?</div>
                                <div :class="icon._rpt_class(data.bonus_payout_completed, data.ready_to_payout)"
                                    v-html="icon._rpt(data.bonus_payout_completed)">
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-row tw-mt-8">
                                <a href="#" class="tw-text-red-600 hover:tw-underline hover:tw-text-red-800"
                                    @click="delete_caretaker_recommendation()">Usuń
                                    polecenie</a>
                            </div>
                        </div>
                        <div class="tw-p-6 tw-bg-gray-300 tw-rounded-tr tw-rounded-br tw-grow">
                            <h2 class="tw-text-2xl tw-font-bold tw-text-center">
                                Dane osoby polecanej
                                <span v-html="icon.lock(data.locked)"></span>
                            </h2>
                            <hr class="tw-my-8">
                            <div class="tw-flex tw-flex-col tw-px-6 tw-mt-4">
                                <div class="tw-grow">
                                    <InputLabel value="Imię" />

                                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1"
                                        v-model="data.caretaker_first_name" />

                                    <InputError class="tw-mt-2"
                                        :message="errors.caretaker_first_name ? errors.caretaker_first_name[0] : ''" />
                                </div>
                                <div class="tw-mt-4 tw-grow">
                                    <InputLabel value="Nazwisko" />

                                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1"
                                        v-model="data.caretaker_last_name" />

                                    <InputError class="tw-mt-2"
                                        :message="errors.caretaker_last_name ? errors.caretaker_last_name[0] : ''" />
                                </div>
                                <div class="tw-mt-4 tw-grow">
                                    <InputLabel value="Adres e-mail" />

                                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1"
                                        v-model="data.caretaker_email" />

                                    <InputError class="tw-mt-2"
                                        :message="errors.caretaker_email ? errors.caretaker_email[0] : ''" />
                                </div>
                                <div class="tw-mt-4 tw-grow">
                                    <InputLabel value="Numer telefonu" />

                                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1"
                                        v-model="data.caretaker_phone_number" />

                                    <InputError class="tw-mt-2"
                                        :message="errors.caretaker_phone_number ? errors.caretaker_phone_number[0] : ''" />
                                </div>
                                <div class="tw-mt-4 tw-grow">
                                    <InputLabel value="Znajomość języka niemieckiego" />
                                    <SelectInput v-model="data.language_id" :options="language" :name_string="'name'"
                                        :class="'tw-mt-2'"></SelectInput>

                                    <InputError class="tw-mt-2"
                                        :message="errors.language_id ? errors.language_id[0] : ''" />
                                </div>
                                <div class="tw-text-center tw-shrink sm:tw-text-right">
                                    <SButton class="tw-mt-4" :class="data.locked ? 'tw-opacity-50' : ''"
                                        :value="disabled ? 'Aktualizowanie...' : 'Aktualizuj dane'" @click="submit"
                                        :disabled="data.locked || disabled ? true : false" :btn_off="data.locked"></SButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </AdminLayout>
</template>
