<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const useAlertStore = AlertStore();
const form = useForm({
    levels: props.levels
});

const submit = () => {
    form.post(route('multiplier.update'), {
        onError: () => {
            useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.');
        },
        onSuccess: () => {
            useAlertStore.pushAlert('success', 'Dane zostały zaktualizowane.');
        }
    })
}

</script>

<template>
    <Head title="VeritasApp - ustawienia mnożników" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Mnożnik punktów</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualne mnożniki</h3>
                        <div class="flex flex-row" v-for="(item, index) in levels">
                            <div class="grow">
                                <span
                                    :class="levelColor(item.id)"><b>{{ level(levels,
                                        item.id).toUpperCase() }}</b>
                                </span>
                            </div>
                            <div class="grow text-right">
                                dzień x {{ item.multiplier.multiplier_value }}
                            </div>
                        </div>
                        <hr class="my-5">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualizuj mnożniki</h3>
                        
                        <form @submit.prevent="submit()" class="mt-4">
                            <div v-for="(item, index) in form.levels" class="mt-4">
                                <InputLabel  :value="`Mnożnik - ${levels[index].name}`" />
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.levels[index].multiplier.multiplier_value"
                                />
                                <InputError class="mt-2" :message="form.errors[`${index}.multiplier.multiplier_value`]" />
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
