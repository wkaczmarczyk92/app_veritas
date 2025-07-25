<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { level, levelColor } from '@/Components/Functions/Level.vue';
import Spinner from '@/Components/Forms/Spinner.vue';
import { AlertStore } from '@/Pinia/AlertStore';
import { ref } from 'vue'
import DisplayRow3 from '@/Components/HTML/DisplayRow3.vue';

const props = defineProps({
    levels: {
        type: Object,
        required: true
    }
});

const form = ref({
    levels: props.levels
});
const alert_store = AlertStore();
const processing = ref(false)
const errors = ref({})

const submit = async () => {
    processing.value = true
    errors.value = {}

    try {
        let response = await axios.patch(route('checkpoints.update'), {
            ...form.value
        })

        response = response.data

        alert_store.pushAlert(response)

        if (response.errors) {
            errors.value = response.errors
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
        title: 'Punkty kontrolne',
        disabled: true
    }
]

</script>

<template>

    <Head title="VeritasApp - ustawienia mnożników" />
    <AdminLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-relative">
            <Spinner v-if="processing" msg="Aktualizuję punkty kontrolne" />
            <v-card rounded="0">
                <v-card-text>
                    <v-card variant="text" title="Aktualne punkty kontrolne">
                        <v-card-text class="!tw-text-lg !tw-mt-10">
                            <DisplayRow3 v-for="(item, index) in levels" :key="index"
                                :value="item.checkpoints.checkpoint + ' pkt'">
                                <template v-slot:name>
                                    <span :class="levelColor(item.id)"><b>{{ level(levels,
                                        item.id).toUpperCase() }}</b>
                                    </span>
                                </template>
                            </DisplayRow3>
                        </v-card-text>
                    </v-card>
                    <v-card title="Aktualizuj punkty kontrolne" variant="text"
                        class="tw-mt-10 !tw-border-t !tw-border-t-2 !tw-border-gray-400" rounded="0">
                        <v-card-text>
                            <div class="tw-flex tw-flex-col tw-gap-4 tw-mt-4">
                                <v-text-field v-for="(item, index) in form.levels" :key="`field_${index}`"
                                    variant="outlined" v-model="form.levels[index].checkpoints.checkpoint" :label="`Mnożnik - ${levels[index].name}`"
                                    :error-messages="errors?.[`levels.${index}.checkpoints.checkpoint`]"></v-text-field>
                            </div>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="#2563eb" variant="tonal" @click="submit()">Aktualizuj</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-card-text>
            </v-card>
        </div>
    </AdminLayout>
</template>
