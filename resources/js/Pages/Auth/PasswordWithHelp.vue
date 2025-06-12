<script setup>

import { ref, watch } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import InputError from '@/Components/InputError.vue';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';

import { AlertStore } from '@/Pinia/AlertStore';

const useAlertStore = AlertStore();

// const props = defineProps({
//     form: {
//         type: Object,
//         required: true
//     }
// });

// const form = ref(props.form);

const emits = defineEmits([
    // 'update:form',
    // 'new-password-request',
    'change-view'
])

const new_password_form_init = () => {
    return {
        pesel: '',
        sms_code: '',
        password: '',
        password_confirmation: '',
        errors: [],
        btns: {
            pesel: {
                disabled: false,
            },
            sms_password: {
                disabled: true,
            },
            password: {
                disabled: true
            }
        },
        show_msg: false,
        show_password_form: false
    }
}

const new_password_form = ref(new_password_form_init())

const new_password_request = async () => {
    new_password_form.value.errors.pesel = '';
    new_password_form.value.btns.pesel.disabled = true;
    let response = await axios.post(route('password.store'), {
        pesel: new_password_form.value.pesel
    });

    response = response.data;

    if (response?.error) {
        new_password_form.value.errors.pesel = response.error.pesel[0];
    }

    if (response.success) {
        new_password_form.value.pesel = '';
    }

    useAlertStore.pushAlert(response.alert_type, response.msg);
    new_password_form.value.btns.pesel.disabled = false;
}

console.log(new_password_form.value);

</script>

<template>
    <div class="tw-px-2 md:tw-px-10">
        <h3 class="tw-font-semibold tw-text-md md:tw-text-lg tw-leading-tight tw-text-left tw-mt-6">
            Zmień hasło z konsultantem
        </h3>
        <div class="tw-text-left tw-mb-10">
            <span @click="$emit('change-view', null)"
                class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline tw-text-sm md:tw-text-md">
                Wróć
            </span>
        </div>
        <div class="tw-flex tw-flex-col">
            <div class="tw-text-left">
                <TInput label="PESEL" v-model:model_value="new_password_form.pesel"
                    :error="new_password_form.errors && new_password_form.errors.pesel ? new_password_form.errors.pesel : ''" />

                <Button value="Chcę zmienić hasło" color="rose" :disabled="new_password_form.btns.pesel.disabled"
                    class="tw-mt-4" @click="new_password_request()" />
            </div>
        </div>
    </div>
</template>
