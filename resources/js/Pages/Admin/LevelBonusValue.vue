<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import MButton from '@/Components/Buttons/MButton.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref, toRef } from 'vue';

import axios from 'axios';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const useAlertStore = AlertStore();
const levels = toRef(props.levels);
const form = ref([
    {
        id: 1,
        value: props.levels[0].bonus_value.value
    },
    {
        id: 2,
        value: props.levels[1].bonus_value.value
    },
    {
        id: 3,
        value: props.levels[2].bonus_value.value
    },
    {
        id: 4,
        value: props.levels[3].bonus_value.value
    },
]);
const disabled = ref(false);
const errors = ref({});

const submit = async () => {
    disabled.value = true;
    errors.value = {};

    let response = await axios.post(route('level.bonus.value.store'), {
        ...form.value
    });

    response = response.data;

    if (response?.errors) {
        errors.value = response.errors;
    } else {
        useAlertStore.pushAlert(response.alert_type, response.msg);
    }

    if (response.success) {
        levels.value = response.levels
    }

    disabled.value = false;
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Bonusy za wejście na poziom',
        disabled: true
    }
]

</script>

<template>
    <Head title="VeritasApp - ustawienia bonusów za wejście na poziom" />
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
                <div class="tw-overflow-hidden tw-bg-gray-100 tw-shadow-xl sm:tw-rounded-lg">
                    <div class="tw-p-6 tw-text-gray-900">
                        <h3 class="tw-mb-6 tw-text-lg tw-font-semibold tw-leading-tight tw-text-gray-800">Aktualne bonusy
                        </h3>
                        <div class="tw-flex tw-flex-row" v-for="(item, index) in levels">
                            <div class="tw-grow">
                                <span :class="levelColor(item.id)"><b>{{ level(levels,
                                    item.id).toUpperCase() }}</b>
                                </span>
                            </div>
                            <div class="tw-text-right tw-grow">
                                {{ item.bonus_value.value }} €
                            </div>
                        </div>
                        <hr class="tw-my-5">
                        <h3 class="tw-mb-6 tw-text-lg tw-font-semibold tw-leading-tight tw-text-gray-800">Aktualizuj bonusy
                        </h3>

                        <form @submit.prevent="submit()" class="tw-mt-4">
                            <div v-for="(item, index) in form" class="tw-mt-4">
                                <InputLabel :value="`Bonus za poziom - ${levels[index].name}`" />
                                <TextInput type="text" class="tw-block tw-w-full tw-mt-1" v-model="form[index].value"
                                    :disabled="index == 0" />
                                <!-- <InputError class="mt-2" :message="errors[`${index}.value`] ? errors[`${index}.value`][0] : ''" /> -->
                            </div>
                            <div class="tw-flex tw-items-center tw-justify-center tw-mt-4">
                                <MButton :disabled="disabled" bg="tw-bg-gray-800" hover="hover:tw-bg-gray-700"
                                    value="Aktualizuj">
                                </MButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
