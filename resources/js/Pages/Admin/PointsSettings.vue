<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore.js';
import MButton from '@/Components/Buttons/MButton.vue';


const props = defineProps({
    settings: {
        type: Object,
        required: true
    }
});

const settings_to_display = ref(props.settings);
const form = useForm(props.settings);
const useAlertStore = AlertStore();

const submit = () => {
    form.post(route('settings.update'), {
        onError: (response) => {
            if (response?.error) {
                useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.')      ; 
            }
        },
        onSuccess: () => {
            settings_to_display.value.points_to_payout = form.points_to_payout;
            settings_to_display.value.payout_cash = form.payout_cash;
            useAlertStore.pushAlert('success', 'Dane zostały zaktualizowane.');
        }
    })
}

</script>

<template>
    <Head title="VeritasApp - ustawienia punktów i kwoty wypłaty" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Ustawienia punktów i kwoty wypłaty</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 overflow-hidden shadow-xl sm:rounded-lg">
                    
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualne ustawienia</h3>
                        <div class="flex flex-row">
                            <div class="grow">
                                Minimalna ilość punktów do wypłaty
                            </div>
                            <div class="grow text-right"><b>{{ settings_to_display.points_to_payout }}</b></div>
                        </div>
                        <div class="flex flex-row">
                            <div class="grow">
                                Kwota do wypłaty za <span class="text-main"><b>{{ settings_to_display.points_to_payout }}</b></span> punktów
                            </div>
                            <div class="grow text-right"><b>{{ settings_to_display.payout_cash }} €</b></div>
                        </div>
                        <hr class="my-5">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualizuj ustawienia</h3>
                        <form @submit.prevent="submit()" class="mt-4">
                            <div class="mt-4">
                                <InputLabel  value="Minimalna ilość punktów do wypłaty " />

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.points_to_payout"
                                />

                                <InputError class="mt-2" :message="form.errors.payout_cash" />
                            </div>
                            <div class="mt-4">
                                <InputLabel  value="Kwota do wypłaty " />

                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.payout_cash"
                                />

                                <InputError class="mt-2" :message="form.errors.payout_cash" />
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                
                                <MButton
                                    :disabled="form.processing"
                                    bg="bg-gray-800"
                                    hover="hover:bg-gray-700"
                                    :add_class="{ 'opacity-25': form.processing }"
                                    value="Aktualizuj"
                                ></MButton>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>
