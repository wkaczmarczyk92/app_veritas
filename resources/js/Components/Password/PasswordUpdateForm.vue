<template>
    <section
        class="tw-bg-gray-100 tw-overflow-hidden tw-shadow-xl tw-rounded-lg tw-px-6 sm:tw-px-20 tw-pt-16 tw-pb-8 sm:tw-pb-12 tw-mt-10 tw-relative">
        <h2 class="tw-text-2xl sm:tw-text-3xl tw-text-gray-800 tw-font-bold tw-relative tw-z-10">
            Zmień hasło
        </h2>
        <div class="tw-flex tw-flex-col tw-mt-10 tw-gap-6">
            <div class="tw-flex tw-flex-col tw-w-full">
                <InputLabel>Aktualne hasło</InputLabel>
                <TextInput v-model="form.current_password" type="password" placeholder="aktualne hasło..." />
                <InputError :message="errors.current_password ? errors.current_password[0] : ''" />
            </div>
            <div class="tw-flex tw-flex-col tw-w-full">
                <InputLabel>Aktualne hasło</InputLabel>
                <TextInput v-model="form.password" type="password" placeholder="nowe hasło..." />
                <InputError :message="errors.password ? errors.password[0] : ''" />
            </div>
            <div class="tw-flex tw-flex-col tw-w-full">
                <InputLabel>Aktualne hasło</InputLabel>
                <TextInput v-model="form.password_confirmation" type="password" placeholder="powtórz nowe hasło..." />
                <InputError :message="errors.password_confirmation ? errors.password_confirmation[0] : ''" />
            </div>
            <MButton value="Zmień hasło" @click="submit()" />
        </div>
    </section>
</template>

<script setup>

import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

import MButton from '@/Components/Buttons/MButton.vue';

import { AlertStore } from '@/Pinia/AlertStore.js';

import { ref } from 'vue';

const form_init = () => {
    return {
        current_password: '',
        password: '',
        password_confirmation: ''
    }
}

const form = ref(form_init())
const errors = ref({});
const alert_store = AlertStore();

const submit = async () => {
    errors.value = {};
    let response = await axios.post(route('password.update'), form.value);
    response = response.data;

    console.log('udpate_password_response', response);

    if (response?.error) {
        errors.value = response.error;
        console.log(errors.value.current_password[0]);
        return;
    }

    alert_store.pushAlert(response.alert_type, response.msg);

    if (response.success) {
        form.value = form_init();
    }
}

</script>
