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

</script>


<template>
    <Head :title="`VeritasApp - ustawienia bonusów za polecenia`" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Ustawienia bonusów za polecenia</h2>
        </template>
        <div class="py-12">
            <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-16">
                <div class="bg-gray-100 shadow-xl rounded p-10">
                    <Header
                        h="4"
                        add_class="font-bold mb-8"
                        value="Aktualne ustawienia bonusów"
                    ></Header>

                    <div class="flex flex-col sm:flex-row justify-between text-gray-800">
                        <div>
                            Bonus za polecenie opiekunki
                        </div>
                        <div class="font-bold text-xl">
                            {{ bonus_ref.caretaker_recommendation.bonus_value }} €
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between mt-1">
                        <div>
                            Bonus za polecenie rodziny
                        </div>
                        <div class="font-bold text-xl">
                            {{ bonus_ref.family_recommendation.bonus_value }} €
                        </div>
                    </div>

                    
                    <hr class="my-8">

                    <Header
                        h="4"
                        add_class="font-bold mb-8"
                        value="Zmień ustawienia bonusów"
                    ></Header>

                    <InputLabel  value="Bonus za polecenie opiekunki" />
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.caretaker_bonus"
                    />
                    <InputError class="mt-2" :message="errors && errors.caretaker_bonus ? errors.caretaker_bonus[0] : ''" />

                    <InputLabel  value="Bonus za polecenie rodziny" class="mt-4"/>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.family_bonus"
                    />
                    <InputError class="mt-2" :message="errors && errors.family_bonus ? errors.family_bonus[0] : ''" />


                    <div class="flex flex-row justify-center mt-4">
                        <MButton
                            @click="submit()"
                            value="Aktualizuj"
                            bg="bg-gray-800"
                            hover="hover:bg-gray-600"
                        ></MButton>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>