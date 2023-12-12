<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Modal from '@/Modals/Modal.vue';
import { ref } from 'vue';
import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import axios from 'axios';
import PasswordWithHelp from './PasswordWithHelp.vue';
import SMSPasswordForm from './SMSPasswordForm.vue';
import NewPasswordForm from './NewPasswordForm.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

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
        }
    });
};

const modal_active = ref(false);
const useAlertStore = AlertStore();
// const pesel = ref('');
// const pesel_error = ref(null);
// const disabled = ref(false);

const active_view = ref(null);
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

const password_reset_view = ref(null);

const change_view = (view) => {
    password_reset_view.value = view;
    new_password_form.value = new_password_form_init();
}


const submit_sms_code = async () => {
    new_password_form.value.errors.pesel = '';
    new_password_form.value.btns.pesel.disabled = true;

    let response = await axios.post(route('one.time.sms.password.store'), {
        pesel: new_password_form.value.pesel
    })

    if (response.data.success) {
        new_password_form.value.errors.pesel = '';
        new_password_form.value.btns.pesel.disabled = true;
        new_password_form.value.btns.sms_password.disabled = false;
    } else {
        new_password_form.value.btns.pesel.disabled = false;
        new_password_form.value.errors.pesel = response.data.errors.pesel[0];
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
        n
    } else {
        new_password_form.value.errors = response.data.errors;
    }
}

</script>

<template>
    <GuestLayout>

        <Head title="Veritas App - zaloguj się" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="login" value="Wpisz swój numer PESEL" />
                <TextInput id="login" type="text" class="mt-1 block w-full" v-model="form.login" autofocus required
                    autocomplete="login" />
                <InputError class="mt-2" :message="form.errors.login" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Hasło" />
                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="current-password" />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-col gap-2 justify-end text-right place-items-end mt-4">
                <p class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline"
                    @click="modal_active = true">
                    Mam problem z zalogowaniem się
                </p>

                <MButton value="Zaloguj się" bg="bg-gray-800" hover="hover:bg-gray-700" add_class="w-fit text-right"
                    :disabled="form.processing">
                </MButton>
            </div>

        </form>

    </GuestLayout>
    <teleport to='body' v-if="modal_active">
        <Modal>
            <div class="w-100 px-2 md:px-10">

                <h2 class="font-semibold text-lg md:text-xl leading-tight text-center mt-6">
                    <i class="fa-sharp fa-solid fa-key text-red-600 mr-2"></i>
                    W jaki sposób otrzymam hasło?
                </h2>
                <div class="flex flex-row justify-center">
                    <p class="text-justify my-10 text-sm">
                        W momencie, gdy uzyskasz od nas akceptację na swój pierwszy wyjazd, otrzymasz dostęp do naszej
                        platformy. Wszystkie niezbędne informacje do zalogowania zostaną przesłane na numer telefonu za
                        pośrednictwem wiadomości SMS w dniu rozpoczęcia zlecenia. W razie dodatkowych pytań lub problemów
                        prosimy dzwonić pod numer <a href="tel:717588140" class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline font-bold">71 758 81 40</a>.
                    </p>
                </div>
                
                

                <Transition name="slide-fade" mode="out-in">
                    <div v-if="password_reset_view == null">
                        <h3 class="font-semibold text-lg leading-tight text-left mt-6">
                            Zapomniałeś/aś hasła?
                        </h3>

                        <div class="flex flex-col mt-4">
                            <!-- <div class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline" @click="pesel_error = ''; password_reset_view = 'by_phone'">Ustaw nowe hasło telefonicznie z konsultantem</div> -->
                            <div class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline" @click="pesel_error = ''; password_reset_view = 'by_sms'">Użyj jednorazowego hasła SMS do zmiany hasła</div>
                        </div>
                    </div>
                    <div v-else-if="password_reset_view != null" class="mt-10 bg-blue-100 p-6 rounded-xl shadow-xl">
                        <Transition name="slide-fade" mode="out-in">
                            
                            <div v-if="password_reset_view == 'by_phone'" class="px-2 md:px-10">
                                <PasswordWithHelp
                                    :form="new_password_form"
                                    @new-password-request="new_password_request()"
                                    @change-view="change_view()"
                                ></PasswordWithHelp>
                            </div>

                            <div v-else-if="password_reset_view == 'by_sms'" class="px-0 md:px-10">
                                <h3 class="font-semibold text-md md:text-lg leading-tight text-left mt-6">
                                    Zmień hasło z jednorazowym kodem SMS
                                </h3>
                                <div class="text-left mb-10">
                                    <span @click="change_view(null)" class="text-blue-600 hover:cursor-pointer hover:text-blue-600 hover:underline text-sm md:text-md">
                                        Wróć
                                    </span>    
                                </div>
                                <div class="flex flex-col">
                                    <Transition name="slide-fade" mode="out-in">
                                        <div class="text-left" v-if="!new_password_form.show_msg && !new_password_form.show_password_form">
                                            <SMSPasswordForm
                                                :form="new_password_form"
                                                @submit-sms-code="submit_sms_code()"
                                                @use-code="use_code()"
                                                @change-view="change_view()"
                                                ></SMSPasswordForm>
                                        </div>
                                        <div class="text-left" v-else-if="new_password_form.show_password_form">
                                            <NewPasswordForm
                                                :form="new_password_form"
                                                @submit-new-password="submit_new_pass()"
                                            ></NewPasswordForm>

                                        </div>
                                        <div class="text-left" v-else-if="new_password_form.show_msg">
                                            <div class="bg-green-600 text-gray-100 font-xl p-6 rounded-xl shadow-lg">Hasło zostało zmienione. Możesz się już zalogować.</div>
                                        </div>
                                    </Transition>
                                    
                                    <div 
                                        class="text-red-600 hover:underline hover:text-red-800 hover:cursor-pointer mt-6"
                                        @click="pesel_error = ''; password_reset_view = 'by_phone'"
                                        >Mam problem ze zmianą hasła przez kod SMS
                                    </div>

                                </div>
                            </div>
                        </Transition>
                    </div>
                </Transition>


                
            </div>
            <div class="text-right mt-6">
                <MButton value="Zamknij" bg="bg-gray-800" hover="hover:bg-gray-700" @click="modal_active = false">
                </MButton>
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

</style>
