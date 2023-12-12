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
    axios.post(route('post.store'), {...new_post.value})
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

</script>

<template>
    <Head :title="`VeritasApp - dodaj post`" />
    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Dodaj post</h2>
        </template>
        <div class="py-12">
            <div class="max-w-8xl sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 shadow-xl rounded sm:rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-12">
                    <h2 class="text-3xl text-gray-800 font-bold">
                        <i class="fa-solid fa-message-plus fa-flip-horizontal text-blue-600"></i>
                        Dodaj nowy post
                    </h2>
                    <hr class="my-4">
                    <InputLabel value="Tytuł"></InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        v-model="new_post.title"
                        placeholder="Tytuł..."
                    />                        
                    <InputError class="mt-2" :message="errors.title ? errors.title[0] : ''" />

                    <div class="flex flex-col sm:flex-row gap-6 mt-6">
                        <div class="grow">
                            <InputLabel class="mb-2" value="Początek publikacji"></InputLabel>
                            <VueDatePicker 
                                v-model="new_post.start_at" 
                                :format="format" 
                                :enable-time-picker="false"
                                auto-apply
                            >
                            </VueDatePicker>
                            <InputError class="mt-2" :message="errors.start_at ? errors.start_at[0] : ''" />
                        </div>
                        <div class="grow">
                            <InputLabel class="mb-2" value="Koniec publikacji"></InputLabel>
                            <VueDatePicker 
                                v-model="new_post.end_at" 
                                :format="format" 
                                :enable-time-picker="false"
                                auto-apply
                            >
                            </VueDatePicker>
                            <InputError class="mt-2" :message="errors.end_at ? errors.end_at[0] : ''" />
                        </div>
                    </div>

                    <InputLabel class="mb-2 mt-6" value="Typ posta"></InputLabel>
                    <SelectInput
                        v-model="new_post.label_id"
                        :options="post_labels"
                        name_string="name"
                    ></SelectInput>
                    <InputError class="mt-2" :message="errors.label_id ? errors.label_id[0] : ''" />

                    <InputLabel class="mb-2 mt-6" value="Treść posta"></InputLabel>
                    <div>
                        <QuillEditor theme="snow" toolbar="full" v-model:content="new_post.body" contentType="html"/>
                        <InputError class="mt-2" :message="errors.body ? errors.body[0] : ''" />
                    </div>

                    <div class="flex flex-row gap-2 my-2">
                        <label>
                            <Checkbox @change="toggle_type($event)" v-model:checked="checked"></Checkbox>
                            <span class="ml-2 text-sm text-gray-600">Szkic</span>
                        </label>
                    </div>

                    <MButton
                        :disabled="disabled"
                        @click="submit"
                        value="Zapisz nowy post"
                        icon="fa-sharp fa-solid fa-floppy-disk-circle-arrow-right"
                        bg="bg-transparent"
                        hover="hover:text-white hover:bg-green-600"
                        color="text-green-600 border border-green-600"
                    ></MButton>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>