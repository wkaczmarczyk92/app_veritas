<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
// import InputError from '@/Components/InputError.vue';
// import InputLabel from '@/Components/InputLabel.vue';
// import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import Modal from '@/Modals/Modal.vue';
import { ref } from 'vue';
// import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';
// import axios from 'axios';
// import PasswordWithHelp from './PasswordWithHelp.vue';
// import SMSPasswordForm from './SMSPasswordForm.vue';
// import NewPasswordForm from './NewPasswordForm.vue';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';

import PasswordRetrieveModal from './PasswordRetrieveModal.vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    errors: {
        type: Object
    }
});

console.log(props.errors)

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: (response) => {
            form.reset('password');
        },
        onError: (err) => {
            console.log(err);
            if (err.email) {
                router.get(route('register.verification', {
                    register_url_token: err.token,
                    p: err.p
                }))
            }
        }
    });
};

const modal_active = ref(false);

</script>

<template>
    <GuestLayout>

        <Head title="Veritas App - zaloguj się" />

        <div v-if="status" class="tw-mb-4 tw-font-medium tw-text-sm tw-text-green-600">
            {{ status }}
        </div>
        <v-alert v-if="errors && errors.email" type="error" class="tw-mb-4">{{ errors.email }}</v-alert>

        <div class="">
            <form @submit.prevent="submit">

                <label for="" class="tw-text-[#666]">Login</label>
                <v-text-field placeholder="Wpisz swój numer PESEL" variant="outlined" rounded="lg" bg-color="#f8f9fa"
                    :error-messages="form.errors && form.errors.login ? form.errors.login : null" v-model="form.login"
                    class="tw-mt-2">
                </v-text-field>
                <!-- <TInput label="Wpisz swój numer PESEL" :error="form.errors.login ? form.errors.login : ''"
                        v-model:model_value="form.login" :clearable="false" /> -->

                <label for="" class="tw-text-[#666]">Hasło</label>
                <v-text-field variant="outlined" rounded="lg" bg-color="#f8f9fa" v-model="form.password"
                    :error-messages="form.errors && form.errors.password ? form.errors.password : null" type="password"
                    class="tw-mt-2" placeholder="Wpisz swoje hasło">
                </v-text-field>

                <div class="tw-flex tw-flex-col tw-gap-2 tw-justify-center tw-text-center tw-items-center tw-mt-4">
                    <p class="tw-text-[#0066cc] hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline"
                        @click="modal_active = true">
                        Mam problem z zalogowaniem się
                    </p>
                    <a :href="route('register.create')"
                        class="tw-text-[#0066cc] hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline">
                        Nie masz konta? <span class="tw-text-[#fc9003] tw-font-bold">Zarejestruj się</span>
                    </a>

                </div>
                <v-btn class="login-btn !tw-text-white tw-w-full tw-mt-4" :disabled="form.processing" type="submit"
                    size="large">
                    Zaloguj się
                </v-btn>

            </form>
        </div>

    </GuestLayout>
    <teleport to='body' v-if="modal_active">
        <Modal>
            <PasswordRetrieveModal />
            <div class="tw-text-right mt-6">
                <Button value="Zamknij" color="red" @click="modal_active = false" />
            </div>
        </Modal>
    </teleport>
</template>

<style>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.login-btn {
    background: linear-gradient(90deg, #0066cc 0%, #0088ff 100%) !important;
}
</style>
