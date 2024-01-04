<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
import { AlertStore } from '@/Pinia/AlertStore';
import MButton from '@/Components/Buttons/MButton.vue';

import axios from 'axios';

const props = defineProps({
    post_labels: {
        type: Object,
        required: true
    }
});

const useAlertStore = AlertStore();

const new_post_init = () => {
    return {
        title: '',
        body: '',
        start_at: '',
        end_at: '',
        type: 'publish',
        label_id: ''
    };
}
const new_post = ref(new_post_init());
const errors = ref({});

watch(new_post, (new_value, old_value) => {
    console.log(new_value);
}, { deep: true });

const disabled = ref(false);


const submit = () => {
    errors.value = {};
    new_post.value.start_at = format(new_post.value.start_at);
    new_post.value.end_at = format(new_post.value.end_at);
    axios.post(route('post.store'), { ...new_post.value })
        .then(response => {
            console.log(response);

            if (!response.data.success) {
                errors.value = response.data.errors;
            }

            if (response.data.success) {
                new_post.value = new_post_init();
                var element = document.getElementsByClassName("ql-editor");
                element[0].innerHTML = "";
                checked.value = false;
                useAlertStore.pushAlert('success', 'Nowy post został dodany.');
            }
        })
}

const checked = ref(false);

const toggle_type = (event) => {
    if (event.target.checked) {
        new_post.value.type = 'draft';
    } else {
        new_post.value.type = 'publish';
    }
}

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
        title: 'Dodaj post',
        disabled: true,
    }
]

</script>

<template>
    <Head :title="`VeritasApp - dodaj post`" />
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
            <div class="tw-px-4 tw-max-w-8xl sm:tw-px-6 lg:tw-px-8">
                <div
                    class="tw-px-6 tw-pt-16 tw-pb-8 tw-bg-gray-100 tw-rounded tw-shadow-xl sm:tw-rounded-lg sm:tw-px-20 sm:tw-pb-12">
                    <h2 class="tw-text-3xl tw-font-bold tw-text-gray-800">
                        <i class="tw-text-blue-600 fa-solid fa-message-plus fa-flip-horizontal"></i>
                        Dodaj nowy post
                    </h2>
                    <hr class="tw-my-4">
                    <InputLabel value="Tytuł"></InputLabel>
                    <TextInput type="text" class="tw-block tw-w-full tw-mt-1" v-model="new_post.title"
                        placeholder="Tytuł..." />
                    <InputError class="tw-mt-2" :message="errors.title ? errors.title[0] : ''" />

                    <div class="tw-flex tw-flex-col tw-gap-6 tw-mt-6 sm:tw-flex-row">
                        <div class="tw-grow">
                            <InputLabel class="tw-mb-2" value="Początek publikacji"></InputLabel>
                            <VueDatePicker v-model="new_post.start_at" :format="format" :enable-time-picker="false"
                                auto-apply>
                            </VueDatePicker>
                            <InputError class="tw-mt-2" :message="errors.start_at ? errors.start_at[0] : ''" />
                        </div>
                        <div class="tw-grow">
                            <InputLabel class="tw-mb-2" value="Koniec publikacji"></InputLabel>
                            <VueDatePicker v-model="new_post.end_at" :format="format" :enable-time-picker="false"
                                auto-apply>
                            </VueDatePicker>
                            <InputError class="tw-mt-2" :message="errors.end_at ? errors.end_at[0] : ''" />
                        </div>
                    </div>

                    <InputLabel class="tw-mt-6 tw-mb-2" value="Typ posta"></InputLabel>
                    <SelectInput v-model="new_post.label_id" :options="post_labels" name_string="name"></SelectInput>
                    <InputError class="tw-mt-2" :message="errors.label_id ? errors.label_id[0] : ''" />

                    <InputLabel class="tw-mt-6 tw-mb-2" value="Treść posta"></InputLabel>
                    <div>
                        <QuillEditor theme="snow" toolbar="full" v-model:content="new_post.body" contentType="html" />
                        <InputError class="tw-mt-2" :message="errors.body ? errors.body[0] : ''" />
                    </div>

                    <div class="tw-flex tw-flex-row tw-gap-2 tw-my-2">
                        <label>
                            <Checkbox @change="toggle_type($event)" v-model:checked="checked"></Checkbox>
                            <span class="tw-ml-2 tw-text-sm tw-text-gray-600">Szkic</span>
                        </label>
                    </div>

                    <MButton :disabled="disabled" @click="submit" value="Zapisz nowy post"
                        icon="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right" bg="tw-bg-transparent"
                        hover="hover:tw-text-white hover:tw-bg-green-600"
                        color="tw-text-green-600 tw-border tw-border-green-600">
                    </MButton>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
