<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { AlertStore } from '@/Pinia/AlertStore';

import TInput from '@/Composables/Form/TInput.vue';
import Button from '@/Composables/Buttons/Button.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AlertWrapper from '@/Components/Alerts/AlertWrapper.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const props = defineProps({
    p: {
        type: Number,
        required: true
    }
})

const form = ref({
    code: '',
});

const errors = ref(null)
const processing = ref(false)

const submit = async () => {
    processing.value = true

    try {
        let response = await axios.patch(route('register.verify', {
            p: props.p
        }), {
            code: form.value.code
        })
        response = response.data
        console.log(response)

        if (response.success) {
            router.get(route('login'));
        }


    } catch (error) {
        console.log(error)
        processing.value = false
    }

    processing.value = false
}

</script>

<template>

    <Head title="Veritas App - weryfikacja sms" />
    <AlertWrapper></AlertWrapper>
    <div
        class="tw-min-h-screen tw-flex tw-flex-col sm:tw-justify-center tw-items-center sm:tw-items-start sm:tw-pl-36 tw-px-4 tw-pt-6 sm:tw-pt-0 tw-bg-gray-800 login-bg">
        <div
            class="tw-w-full sm:tw-max-w-2xl tw-mt-6 tw-px-6 tw-py-4 tw-bg-white tw-shadow-xl tw-shadow-gray-600 tw-overflow-hidden sm:tw-rounded-lg">
            <div class="tw-flex tw-flex-row tw-justify-center tw-mb-10 tw-mt-4">
                <Link href="/">
                <ApplicationLogo class="tw-w-auto tw-h-24 tw-fill-current tw-text-gray-500" />
                </Link>
            </div>

            <div class="tw-grid tw-grid-cols-1 tw-mt-6 tw-justify-center tw-text-center">
                <label for="" class="tw-text-lg">Wpisz kod aktywacyjny</label>
                <v-otp-input placeholder="?" v-model="form.code"></v-otp-input>
            </div>

            <div class="tw-flex tw-flex-col tw-gap-2 tw-justify-center tw-mt-4">
                <Button type="submit" value="Aktywuj konto" variant="tonal" color="#16a34a" :disabled="processing"
                    @click="submit()" />
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
