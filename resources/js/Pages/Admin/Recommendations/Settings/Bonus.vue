<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { toRef, ref, watch } from 'vue';
import { AlertStore } from '@/Pinia/AlertStore';
import DisplayRow3 from '@/Components/HTML/DisplayRow3.vue';
import Spinner from '@/Components/Forms/Spinner.vue';


const props = defineProps({
    bonus: {
        type: Object,
        required: true
    }
});

const bonus_ref = toRef(props.bonus);
console.log(bonus_ref);

const form = ref({
    caretaker_bonus: props.bonus.caretaker_recommendation.bonus_value,
    family_bonus: props.bonus.family_recommendation.bonus_value
});


const errors = ref();
const alert_store = AlertStore();
const processing = ref(false)

const submit = async () => {
    errors.value = {};
    processing.value = true

    try {
        let response = await axios.patch(route('bonus.update'), { ...form.value });
        response = response.data

        if (response?.errors) {
            errors.value = response.errors;
        }

        alert_store.pushAlert(response);

        if (response.success) {
            bonus_ref.value = response.bonus;
        }
    } catch (error) {
        console.log(error)
        alert_store.exception()
    } finally {
        processing.value = false
    }
}

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Ustawienia bonusów',
        disabled: true
    }
]

</script>


<template>

    <Head :title="`VeritasApp - ustawienia bonusów za polecenia`" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-relative">
            <Spinner v-if="processing" msg="Aktualizacja bonusów" />
            <v-card rounded="0">
                <v-card-text>
                    <v-card title="Aktualne ustawienia bonusów" variant="text">
                        <v-card-text class="!tw-text-lg !tw-mt-4">
                            <DisplayRow3 name="Polecenia opiekunki"
                                :value="`${bonus_ref.caretaker_recommendation.bonus_value} €`" />
                            <DisplayRow3 name="Polecenia rodziny"
                                :value="`${bonus_ref.family_recommendation.bonus_value} €`" />
                        </v-card-text>
                    </v-card>
                    <v-card title="Zmień ustawienia bonusów" variant="text"
                        class="tw-mt-10 !tw-border-t !tw-border-t-2 !tw-border-gray-400" rounded="0">
                        <v-card-text class="tw-mt-4">
                            <v-text-field variant="outlined" label="Bonus za polecenie opiekunki"
                                v-model="form.caretaker_bonus" :error-messages="errors?.caretaker_bonus?.[0] ?? null" />
                            <v-text-field variant="outlined" label="Bonus za polecenie opiekunki"
                                v-model="form.family_bonus" :error-messages="errors?.family_bonus?.[0] ?? null" />
                        </v-card-text>
                    </v-card>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="#2563eb" variant="tonal" @click="submit()">Aktualizuj</v-btn>
                </v-card-actions>
            </v-card>
        </div>
    </AdminLayout>
</template>
