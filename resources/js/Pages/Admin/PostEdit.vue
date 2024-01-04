<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { format } from '@/Components/Functions/DateFormat.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import SButton from '@/Components/SButton.vue';
import AlertSuccess from '@/Components/Functions/AlertSuccess.vue';
import { ref } from 'vue';
import axios from 'axios';

import { AlertStore } from '@/Pinia/AlertStore';


const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    post_labels: {
        type: Object,
        required: true
    }
});

const useAlertStore = AlertStore();

const post = ref(props.post);
const checked = ref(false);
checked.value = post.value.type == 'draft' ? true : false;

const errors = ref({});
const toggle_type = (event) => {
    if (event.target.checked) {
        post.value.type = 'draft';
    } else {
        post.value.type = 'publish';
    }
}

const submit = () => {
    errors.value = {};
    post.value.start_at = format(post.value.start_at);
    post.value.end_at = format(post.value.end_at);
    axios.patch(route('post.update', {
        id: post.value.id
    }), { ...post.value }).then(response => {
        console.log(response);

        if (!response.data.success) {
            errors.value = response.data.errors;
        }

        if (response.data.success) {
            useAlertStore.pushAlert('success', 'Post został zaktualizowany.');
            // success.value = true;
            closeSuccess();
        }
    })
}

const closeSuccess = () => {
    setTimeout(() => {
        success.value = false;
    }, 5000);
}

const success = ref(false);

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('dashboard')
    },
    {
        title: 'Wszystkie posty',
        disabled: false,
        href: route('post.index')
    },
    {
        title: 'Edytuj post #' + props.post.id,
        disabled: true,
    }
]

</script>

<template>
    <Head :title="`VeritasApp - edycja posta`" />
    <AdminLayout>
        <!-- <AlertSuccess :position_fixed="true" v-model="success" v-if="success">Post został zaktualizowany.</AlertSuccess> -->
        <template #header>
            <!-- <h2 class="text-xl font-semibold leading-tight text-gray-200">Użytkownicy</h2> -->
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>
        <div class="tw-py-12">
            <div class="tw-px-4 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-px-6 tw-pt-16 tw-pb-8 tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg sm:tw-px-20 sm:tw-pb-12">
                    <div class="tw-flex tw-flex-col tw-justify-between sm:tw-flex-row">
                        <h2 class="tw-text-3xl tw-font-bold tw-text-gray-800">
                            <i class="tw-text-purple-600 fa-regular fa-file-pen"></i>
                            Edytuj post
                        </h2>
                        <div class="tw-mt-2">
                            <a :href="route('post.index')"
                                class="tw-text-blue-600 hover:tw-text-blue-800 hover:tw-cursor-pointer">
                                <i class="tw-mr-1 fa-solid fa-arrow-left"></i>
                                Wróć do wszystkich postów
                            </a>
                        </div>
                    </div>

                    <hr class="tw-my-4">
                    <InputLabel value="Tytuł"></InputLabel>
                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1" v-model="post.title" placeholder="Tytuł..." />
                    <InputError class="tw-mt-2" :message="errors.title ? errors.title[0] : ''" />

                    <div class="tw-flex tw-flex-col tw-gap-6 tw-mt-6 sm:tw-flex-row">
                        <div class="tw-grow">
                            <InputLabel class="tw-mb-2" value="Początek publikacji"></InputLabel>
                            <VueDatePicker v-model="post.start_at" :format="format" :enable-time-picker="false" auto-apply>
                            </VueDatePicker>
                            <InputError class="tw-mt-2" :message="errors.start_at ? errors.start_at[0] : ''" />
                        </div>
                        <div class="tw-grow">
                            <InputLabel class="tw-mb-2" value="Koniec publikacji"></InputLabel>
                            <VueDatePicker v-model="post.end_at" :format="format" :enable-time-picker="false" auto-apply>
                            </VueDatePicker>
                            <InputError class="tw-mt-2" :message="errors.end_at ? errors.end_at[0] : ''" />
                        </div>
                    </div>

                    <InputLabel class="tw-mt-6 tw-mb-2" value="Typ posta"></InputLabel>
                    <SelectInput v-model="post.label_id" :options="post_labels" name_string="name"></SelectInput>
                    <InputError class="tw-mt-2" :message="errors.label_id ? errors.label_id[0] : ''" />

                    <InputLabel class="tw-mt-6 tw-mb-2" value="Treść posta"></InputLabel>

                    <div>
                        <QuillEditor theme="snow" toolbar="full" v-model:content="post.body" contentType="html" />
                        <InputError class="mt-2" :message="errors.body ? errors.body[0] : ''" />
                    </div>

                    <div class="tw-flex tw-flex-row tw-gap-2 tw-my-2">
                        <label>
                            <Checkbox @change="toggle_type($event)" v-model:checked="checked"></Checkbox>
                            <span class="tw-ml-2 tw-text-sm tw-text-gray-600">Szkic</span>
                        </label>
                    </div>


                    <SButton icon="<i class='tw-mr-2 fa-sharp fa-solid fa-floppy-disk-circle-arrow-right'></i>"
                        class="tw-mt-6" value="Aktualizuj post" @click="submit"></SButton>

                </div>
            </div>
        </div>

    </AdminLayout>
</template>
