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

</script>

<template>
    <Head title="VeritasApp - ustawienia bonusów za wejście na poziom" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Bonusy za wejście na poziom</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualne bonusy</h3>
                        <div class="flex flex-row" v-for="(item, index) in levels">
                            <div class="grow">
                                <span
                                    :class="levelColor(item.id)"><b>{{ level(levels,
                                        item.id).toUpperCase() }}</b>
                                </span>
                            </div>
                            <div class="grow text-right">
                                {{ item.bonus_value.value }} €
                            </div>
                        </div>
                        <hr class="my-5">
                        <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-6">Aktualizuj bonusy</h3>
                        
                        <form @submit.prevent="submit()" class="mt-4">
                            <div v-for="(item, index) in form" class="mt-4">
                                <InputLabel  :value="`Bonus za poziom - ${levels[index].name}`" />
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form[index].value"
                                    :disabled="index == 0"
                                />
                                <!-- <InputError class="mt-2" :message="errors[`${index}.value`] ? errors[`${index}.value`][0] : ''" /> -->
                            </div>
                            <div class="flex items-center justify-center mt-4">
                                <MButton
                                    :disabled="disabled"
                                    bg="bg-gray-800"
                                    hover="hover:bg-gray-700"
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
