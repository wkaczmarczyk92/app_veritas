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


</script>
<template>
    <Head title="VeritasApp - opiekunka" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Opiekunka</h2>
        </template>
        
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-16">
                <div class="bg-gray-100 rounded shadow-xl">
                    <div class="flex flex-col md:flex-row justify-between gap-10">
                        <div class="md:w-1/3 p-6">
                            <h2 class="font-bold text-2xl">Dane osoby polecającej</h2>
                            <hr class="my-8">
                            <div class="flex flex-row justify-between text-sm">
                                <div>Imię i nazwisko</div>
                                <div>
                                    {{ `${data.user.user_profiles.first_name} ${data.user.user_profiles.last_name}` }}
                                </div>
                            </div>
                            <div class="flex flex-row justify-between text-sm mt-2">
                                <div>Adres e-mail</div>
                                <div>
                                    {{ data.user.user_profiles.email }}
                                </div>
                            </div>
                            <div class="flex flex-row justify-between text-sm mt-2">
                                <div>Numer telefonu</div>
                                <div>
                                    {{ data.user.user_profiles.phone_number }}
                                </div>
                            </div>
                            <div class="flex flex-row justify-between mt-2 text-sm">
                                <div>Przejdź do profilu</div>
                                <div>
                                    <a class="edit-user" :href="`/uzytkownik/${data.user.id}`">
                                        <i class="fa-solid fa-user-pen text-lg"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="flex flex-row justify-between mt-6 text-sm">
                                <div>Utworzono</div>
                                <div>
                                    {{ format(data.created_at) }}
                                </div>
                            </div>

                            <h2 class="font-bold text-2xl mt-16">Dodatkowe informacje</h2>
                            <hr class="my-8">
                            <div class="flex flex-row justify-between mt-2 text-sm">
                                <div>Bonus gotowy do wypłaty?</div>
                                <div :class="icon._rpt_class(data.bonus_payout_completed, data.ready_to_payout)" v-html="icon._rpt(data.bonus_payout_completed)">
                                </div>
                            </div>
                        </div>
                        <div class="grow bg-gray-300 p-6 rounded-tr rounded-br">
                            <h2 class="font-bold text-2xl text-center">
                                Dane osoby polecanej                                
                                <span v-html="icon.lock(data.locked)"></span>
                            </h2>
                            <hr class="my-8">
                            <div class="flex flex-col px-6 mt-4">
                                <div class="grow">
                                    <InputLabel  value="Imię" />

                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="data.caretaker_first_name"
                                    />

                                    <InputError class="mt-2" :message="errors.caretaker_first_name ? errors.caretaker_first_name[0] : ''" />
                                </div>
                                <div class="grow mt-4">
                                    <InputLabel  value="Nazwisko" />

                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="data.caretaker_last_name"
                                    />

                                    <InputError class="mt-2" :message="errors.caretaker_last_name ? errors.caretaker_last_name[0] : ''" />
                                </div>
                                <div class="grow mt-4">
                                    <InputLabel  value="Adres e-mail" />

                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="data.caretaker_email"
                                    />

                                    <InputError class="mt-2" :message="errors.caretaker_email ? errors.caretaker_email[0] : ''" />
                                </div>
                                <div class="grow mt-4">
                                    <InputLabel  value="Numer telefonu" />

                                    <TextInput
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="data.caretaker_phone_number"
                                    />

                                    <InputError class="mt-2" :message="errors.caretaker_phone_number ? errors.caretaker_phone_number[0] : ''" />
                                </div>
                                <div class="grow mt-4">
                                    <InputLabel  value="Znajomość języka niemieckiego" />
                                    <SelectInput v-model="data.language_id" :options="language" :name_string="'name'" :class="'mt-2'"></SelectInput>

                                    <InputError class="mt-2" :message="errors.language_id ? errors.language_id[0] : ''" />
                                </div>
                                <div class="shrink text-center sm:text-right">
                                    <SButton
                                        class="mt-4"
                                        :class="data.locked ? 'opacity-50' : ''"
                                        :value="disabled ? 'Aktualizowanie...' : 'Aktualizuj dane'"
                                        @click="submit"
                                        :disabled="data.locked || disabled ? true : false"
                                        :btn_off="data.locked"
                                    ></SButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </AdminLayout>
</template>
