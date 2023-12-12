<template>
    <div class="bg-gray-100 shadow-xl rounded p-10">
        <h2 class="font-semibold text-2xl leading-tight text-center mb-10">
            <i class="fa-sharp fa-solid fa-key text-red-600 mr-2"></i>
            Zmiana hasła
        </h2>
        <div class="max-w-full sm:max-w-3xl ml-auto mr-auto mb-10" v-if="user.password_requests.length > 0">
            <div class="flex flex-col justify-center">
                <InputLabel for="password" value="Hasło" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="password_error ? password_error : ''" />
            </div>
            <div class="flex flex-col justify-center mt-4">

                    <InputLabel for="password" value="Powtórz hasło" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                    />
            </div>
            <MButton
                value="Zmień hasło"
                add_class="mt-4"
                @click="update()"
            ></MButton>
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