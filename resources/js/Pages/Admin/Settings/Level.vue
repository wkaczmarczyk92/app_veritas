<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref, toRef } from 'vue';
import DisplayRow3 from '@/Components/HTML/DisplayRow3.vue';
import Spinner from '@/Components/Forms/Spinner.vue';


const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const alert_store = AlertStore();
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
const processing = ref(false);
const errors = ref({});

const submit = async () => {
    processing.value = true;
    errors.value = {};

    try {
        let response = await axios.post(route('level.bonus.value.store'), {
            ...form.value
        });

        response = response.data;

        alert_store.pushAlert(response)

        if (response?.errors) {
            errors.value = response.errors;
        }

        if (response.success) {
            levels.value = response.levels
        }
    } catch (error) {
        console.log(error)
        alert_store.exception()
    } finally {
        processing.value = false;
    }
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
        <div class="tw-relative">
            <Spinner v-if="processing" msg="Aktualizacja bonusów" />
            <v-card rounded="0">
                <v-card-text>
                    <v-card title="Aktualne bonusy" variant="text">
                        <v-card-text class="!tw-text-lg tw-mt-10">
                            <DisplayRow3 v-for="(item, index) in levels" :key="index"
                                :value="`${item.bonus_value.value} €`">
                                <template v-slot:name>
                                    <span :class="levelColor(item.id)"><b>{{ level(levels,
                                        item.id).toUpperCase() }}</b>
                                    </span>
                                </template>
                            </DisplayRow3>
                        </v-card-text>
                    </v-card>
                    <v-card rounded="0" title="Aktualizuj bonusy" variant="text"
                        class="tw-mt-10 !tw-border-t !tw-border-t-2 !tw-border-gray-400">
                        <v-card-text class="tw-mt-4">
                            <div class="tw-flex tw-flex-col tw-gap-4">
                                <v-text-field v-for="(item, index) in form" :key="`field_${index}`"
                                    :error-messages="errors?.[`${index}.value`]" variant="outlined"
                                    :disabled="index === 0" v-model="form[index].value"
                                    :label="levels[index].name.toUpperCase()"></v-text-field>
                            </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn variant="tonal" color="#2563eb" @click="submit()">Aktualizuj</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-card-text>
            </v-card>
        </div>
    </AdminLayout>
</template>
