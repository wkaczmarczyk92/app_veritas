<script setup>

import PasswordWithHelp from './PasswordWithHelp.vue';
import OneTimePasswordForm from './OneTimePasswordForm.vue';

import { ref } from 'vue'

const password_reset_view = ref(null);

const change_view = (view) => {
    console.log(view)
    password_reset_view.value = view;
}

</script>

<template>
    <div class="tw-w-100 tw-px-2 md:tw-px-10">

        <h2 class="tw-font-semibold tw-text-lg md:tw-text-xl tw-leading-tight tw-text-center tw-mt-6">
            <i class="fa-sharp fa-solid fa-key tw-text-red-600 tw-mr-2"></i>
            W jaki sposób otrzymam hasło?
        </h2>
        <div class="tw-flex flex-row tw-justify-center">
            <p class="tw-text-justify tw-my-10 tw-text-sm">
                W momencie, gdy uzyskasz od nas akceptację na swój pierwszy wyjazd, otrzymasz dostęp do naszej
                platformy. Wszystkie niezbędne informacje do zalogowania zostaną przesłane na numer telefonu za
                pośrednictwem wiadomości SMS w dniu rozpoczęcia zlecenia. W razie dodatkowych pytań lub
                problemów
                prosimy dzwonić pod numer <a href="tel:717588140"
                    class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline tw-font-bold">71
                    758
                    81 40</a>.
            </p>
        </div>

        <Transition name="slide-fade" mode="out-in">
            <div v-if="password_reset_view == null">
                <h3 class="tw-font-semibold tw-text-lg tw-leading-tight tw-text-left tw-mt-6">
                    Zapomniałeś/aś hasła?
                </h3>

                <div class="tw-flex tw-flex-col tw-mt-4">
                    <div class="tw-text-blue-600 hover:tw-cursor-pointer hover:tw-text-blue-600 hover:tw-underline"
                        @click="password_reset_view = 'by_sms'">Użyj jednorazowego hasła SMS
                        do
                        zmiany hasła</div>
                </div>
            </div>
            <v-card v-else-if="password_reset_view != null" text="asasd" variant="outlined">
                <template v-slot:text>
                    <Transition name="slide-fade" mode="out-in">

                        <PasswordWithHelp v-if="password_reset_view == 'by_phone'" @change-view="change_view" />

                        <OneTimePasswordForm v-else-if="password_reset_view == 'by_sms'" @change-view="change_view" />
                    </Transition>
                </template>
            </v-card>
        </Transition>



    </div>

</template>

<style>
.v-card-text {
    color: black;
}
</style>