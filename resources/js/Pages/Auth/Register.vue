<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AlertStore } from '@/Pinia/AlertStore';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { register } from 'quill-image-resize-vue';

const form = ref({
    pesel: '',
    first_name: '',
    last_name: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    code: '',
});

const errors = ref(null)
const processing = ref(false)
const show_password = ref(false)

const submit = async () => {
    processing.value = true

    try {
        let response = await axios.post(route('register.store'), form.value)
        response = response.data

        if (response.errors) {
            errors.value = response.errors
        }

        if (response.success) {
            router.get(route('register.verification', {
                register_url_token: response.register_url_token,
                p: response.p
            }))
        }

        // form.value.show_code_window = true
    } catch (error) {
        console.log(error)
        processing.value = false
    }

    processing.value = false
}

</script>

<template>

    <Head title="Veritas App - zarejestruj się" />

    <AlertWrapper></AlertWrapper>
    <div class="tw-min-h-screen tw-flex tw-flex-col sm:tw-pt-0 tw-bg-gray-100 tw-justify-center tw-py-10">
        <div class="tw-flex tw-flex-col tw-justify-center tw-items-center lg:tw-p-10 tw-p-2">
            <div class="tw-w-full lg:tw-max-w-3xl tw-px-6 tw-py-4 tw-bg-white tw-shadow-lg tw-shadow-gray-600
                tw-overflow-hidden tw-rounded-lg tw-border-t tw-border-[#fc9003] tw-border-t-8">
                <div class="tw-flex tw-flex-row tw-justify-center">
                    <Link href="/">
                    <ApplicationLogo class="tw-w-auto tw-h-24 tw-fill-current tw-text-gray-500" />
                    </Link>
                </div>
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-2 tw-mt-10">
                    <div>
                        <label for="" class="tw-text-[#666]">PESEL (wymagane)</label>
                        <v-text-field placeholder="PESEL..." variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.pesel ? errors.pesel[0] : null" v-model="form.pesel"
                            class="tw-mt-2">
                        </v-text-field>
                    </div>
                    <div>
                        <label for="" class="tw-text-[#666]">Numer telefonu (wymagane)</label>
                        <v-text-field placeholder="Numer telefonu..." variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.phone_number ? errors.phone_number[0] : null"
                            v-model="form.phone_number" class="tw-mt-2">
                        </v-text-field>
                    </div>
                </div>
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-2 tw-mt-2">
                    <div>
                        <label for="" class="tw-text-[#666]">Imię (wymagane)</label>
                        <v-text-field placeholder="Imię.." variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.pesel ? errors.first_name[0] : null"
                            v-model="form.first_name" class="tw-mt-2">
                        </v-text-field>
                    </div>
                    <div>
                        <label for="" class="tw-text-[#666]">Nazwisko (wymagane)</label>
                        <v-text-field placeholder="Nazwisko..." variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.last_name ? errors.last_name[0] : null"
                            v-model="form.last_name" class="tw-mt-2">
                        </v-text-field>
                    </div>
                </div>
                <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-2 tw-gap-2 tw-mt-2">
                    <div>
                        <label for="" class="tw-text-[#666]">Hasło</label>
                        <v-text-field :type="show_password ? 'text' : 'password'" placeholder="Hasło.."
                            variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.password ? errors.password[0] : null"
                            v-model="form.password" class="tw-mt-2">
                            <template v-slot:append-inner>
                                <div v-if="!show_password">
                                    <i class="fa-solid fa-eye tw-text-xl hover:tw-cursor-pointer hover:tw-text-blue-600"
                                        @click="show_password = true"></i>
                                </div>
                                <div v-else>
                                    <i class="fa-solid fa-eye-slash tw-text-xl hover:tw-cursor-pointer hover:tw-text-blue-600"
                                        @click="show_password = false"></i>
                                </div>
                            </template>
                        </v-text-field>
                    </div>
                    <div>
                        <label for="" class="tw-text-[#666]">Powtórz hasło</label>
                        <v-text-field :type="show_password ? 'text' : 'password'" placeholder="Powtórz hasło..."
                            variant="outlined" rounded="lg" bg-color="#f8f9fa"
                            :error-messages="errors && errors.password_confirmation ? errors.password_confirmation[0] : null"
                            v-model="form.password_confirmation" class="tw-mt-2">
                        </v-text-field>
                    </div>
                </div>

                <div class="tw-flex tw-flex-col tw-gap-2 tw-justify-center tw-mt-4">
                    <a class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline"
                        :href="route('login')">
                        Masz już konto? Zaloguj się
                    </a>

                    <Button type="submit" value="Zarejestruj się" variant="tonal" color="blue" :disabled="processing"
                        @click="submit()" />
                </div>
            </div>
        </div>
    </div>
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
