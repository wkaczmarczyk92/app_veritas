<template>
    <div class="tw-bg-gray-100 tw-shadow-xl tw-rounded tw-p-10">
        <h2 class="tw-font-semibold tw-text-2xl tw-leading-tight tw-text-center tw-mb-10">
            <i class="fa-sharp fa-solid fa-key tw-text-red-600 tw-mr-2"></i>
            Zmiana hasła
        </h2>
        <div class="tw-max-w-full sm:tw-max-w-3xl tw-ml-auto tw-mr-auto tw-mb-10" v-if="user.password_requests.length > 0">
            <div class="tw-flex tw-flex-col tw-justify-center">
                <InputLabel for="password" value="Hasło" />

                <TextInput id="password" type="password" class="tw-mt-1 tw-block tw-w-full" v-model="form.password" required
                    autocomplete="current-password" />
                <InputError class="mt-2" :message="password_error ? password_error : ''" />
            </div>
            <div class="tw-flex tw-flex-col tw-justify-center tw-mt-4">

                <InputLabel for="password" value="Powtórz hasło" />

                <TextInput id="password" type="password" class="tw-mt-1 tw-block tw-w-full"
                    v-model="form.password_confirmation" required />
            </div>
            <MButton value="Zmień hasło" add_class="tw-mt-4" @click="update()"></MButton>
        </div>
        <StaticInfoAlert v-else>Brak możliwośći zmiany hasła.</StaticInfoAlert>
    </div>
</template>

<script setup>

import { PasswordRequestStore } from '@/Pinia/PasswordRequestStore';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import StaticInfoAlert from '@/Components/Alerts/StaticInfoAlert.vue';

import { ref, toRef } from 'vue';

const usePasswordRequestStore = PasswordRequestStore();
const useAlertStore = AlertStore();

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const user = toRef(props.user)

console.log(props.user.password_requests)

const form = ref({
    password: '',
    password_confirmation: '',
    user_id: props.user.id
})

const password_error = ref('');

const update = async () => {
    let response = await usePasswordRequestStore.update(form.value);
    console.log(response);

    if (response?.errors) {
        password_error.value = response.errors.password[0];
    }

    if (response?.alert_type) {
        useAlertStore.pushAlert(response.alert_type, response.msg);
    }

    if (response.success) {
        usePasswordRequestStore.countPasswordRequests();
        form.value.password = '';
        form.value.password_confirmation = '';

        user.value.password_requests = {};
    }
}

</script>
