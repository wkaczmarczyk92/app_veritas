<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import MButton from '@/Components/Buttons/MButton.vue';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const form = useForm({
    levels: props.levels
});
const useAlertStore = AlertStore();

const submit = () => {
    form.patch(route('checkpoints.update'), {
        onError: (response) => {
            console.log(response);
            useAlertStore.pushAlert('danger', 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.');
        },
        onSuccess: () => {
            useAlertStore.pushAlert('success', 'Dane zostały zaktualizowane.');
        }
    })
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Punkty kontrolne',
        disabled: true
    }
]

</script>

<template>
    <Head title="VeritasApp - ustawienia mnożników" />
    <AdminLayout>
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <div class="tw-py-12">
            <div class="tw-max-w-4xl tw-px-4 tw-mx-auto sm:tw-px-6 lg:tw-px-8">
                <div class="tw-overflow-hidden tw-bg-white tw-shadow-xl sm:tw-rounded-lg">
                    <div class="tw-p-6 tw-text-gray-900">
                        <h3 class="tw-mb-6 tw-text-lg tw-font-semibold tw-leading-tight tw-text-gray-800">Aktualne punkty
                            kontrolne</h3>
                        <div class="tw-flex tw-flex-row" v-for="(item, index) in levels">
                            <div class="tw-grow">
                                <span :class="levelColor(item.id)"><b>{{ level(levels,
                                    item.id).toUpperCase() }}</b>
                                </span>
                            </div>
                            <div class="tw-text-right tw-grow">
                                {{ item.checkpoints.checkpoint }}
                            </div>
                        </div>
                        <hr class="tw-my-5">
                        <h3 class="tw-mb-6 tw-text-lg tw-font-semibold tw-leading-tight tw-text-gray-800">Aktualizuj punkty
                            kontrolne</h3>

                        <form @submit.prevent="submit()" class="tw-mt-4">
                            <div v-for="(item, index) in form.levels" class="tw-mt-4">
                                <InputLabel :value="`Mnożnik - ${levels[index].name}`" />

                                <TextInput type="text" class="tw-block tw-w-full tw-mt-1"
                                    v-model="form.levels[index].checkpoints.checkpoint"
                                    :disabled="index == 0 ? true : false" />

                                <InputError class="tw-mt-2" :message="form.errors[`${index}.checkpoints.checkpoint`]" />
                            </div>
                            <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
                                <MButton :disabled="form.processing" bg="tw-bg-gray-800" hover="hover:tw-bg-gray-700"
                                    :add_class="{ 'tw-opacity-25': form.processing }" value="Aktualizuj"></MButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
