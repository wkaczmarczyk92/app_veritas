<script setup>

import SMSPasswordForm from './SMSPasswordForm.vue';
import NewPasswordForm from './NewPasswordForm.vue';

import { ref, watch } from 'vue'

import { AlertStore } from '@/Pinia/AlertStore';

const useAlertStore = AlertStore();

const emits = defineEmits([
    'change-view'
])

// emits('change-view', 'test')

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
            },
            password_form: {
                disabled: false
            }
        },
        show_msg: false,
        show_password_form: false
    }
}

const new_password_form = ref(new_password_form_init())

watch(
    () => new_password_form.value.pesel,
    (new_value, old_value) => {
        console.log('zmiany pesel rodzic', new_value)
    }
)

const form_view = ref('sms_form')
// password_form

const submit_sms_code = async () => {
    new_password_form.value.errors.pesel = '';
    new_password_form.value.btns.pesel.disabled = true;

    let response = await axios.post(route('one.time.sms.password.store'), {
        pesel: new_password_form.value.pesel
    })

    response = response.data

    console.log(response)

    useAlertStore.pushAlert(response);

    if (response.success) {
        new_password_form.value.errors.pesel = '';
        new_password_form.value.btns.pesel.disabled = true;
        new_password_form.value.btns.sms_password.disabled = false;

        if (response.replay) {
            alert('SMS już został dzisiaj do Ciebie wysłany.');
        }
    } else {
        new_password_form.value.btns.pesel.disabled = false;
        new_password_form.value.errors.pesel = response.errors.pesel[0];
    }
}

const use_code = async () => {
    new_password_form.value.errors.sms_code = '';
    new_password_form.value.btns.sms_password.disabled = true;

    let response = await axios.patch(route('one.time.sms.password.update'), {
        pesel: new_password_form.value.pesel,
        code: new_password_form.value.sms_code
    })

    if (response.data.success) {
        new_password_form.value.show_password_form = true;

    } else {
        new_password_form.value.errors.sms_code = response.data.errors?.code ?? '';
        new_password_form.value.btns.sms_password.disabled = false;
    }
}

const submit_new_pass = async () => {
    new_password_form.value.errors = {};
    new_password_form.value.btns.password_form.disabled = true;

    let response = await axios.patch(route('submit.new.pass.with.sms.code'), {
        pesel: new_password_form.value.pesel,
        code: new_password_form.value.sms_code,
        password: new_password_form.value.password,
        password_confirmation: new_password_form.value.password_confirmation
    });

    if (response.data.success) {
        new_password_form.value = new_password_form_init();
        new_password_form.value.show_password_form = false;
        new_password_form.value.show_msg = true;
    } else {
        new_password_form.value.errors = response.data.errors;
        new_password_form.value.btns.password_form.disabled = false;
    }
}

</script>

<template>
    <div class="tw-px-0 md:tw-px-10">
        <h3 class="tw-font-semibold tw-text-md md:tw-text-lg tw-leading-tight tw-text-left tw-mt-6">
            Zmień hasło z jednorazowym kodem SMS
        </h3>
        <div class="tw-text-left tw-mb-10">
            <span @click="$emit('change-view', null)"
                class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline tw-text-sm md:tw-text-md">
                Wróć
            </span>
        </div>
        <div class="tw-flex tw-flex-col">
            <Transition name="slide-fade" mode="out-in">
                <div class="tw-text-left" v-if="!new_password_form.show_msg && !new_password_form.show_password_form">
                    <SMSPasswordForm v-model:form="new_password_form" @submit-sms-code="submit_sms_code()"
                        @use-code="use_code()"></SMSPasswordForm>
                </div>
                <div class="tw-text-left" v-else-if="new_password_form.show_password_form">
                    <NewPasswordForm v-model:form="new_password_form" @submit-new-password="submit_new_pass()">
                    </NewPasswordForm>

                </div>
                <div class="tw-text-left" v-else-if="new_password_form.show_msg">
                    <v-alert color="success" icon="$success" class="!tw-py-8"
                        text="Hasło zostało zmienione. Możesz się już zalogować." />
                </div>
            </Transition>

            <div class="tw-text-red-600 hover:tw-underline hover:tw-text-red-800 hover:tw-cursor-pointer tw-mt-6"
                @click="$emit('change-view', 'by_phone')">Mam problem ze
                zmianą
                hasła przez kod SMS
            </div>

        </div>
    </div>
</template>