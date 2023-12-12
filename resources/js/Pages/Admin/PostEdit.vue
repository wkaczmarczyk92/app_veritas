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
    }), {...post.value}).then(response => {
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


</script>

<template>
    <Head :title="`VeritasApp - edycja posta`" />
    <AdminLayout>
        <!-- <AlertSuccess :position_fixed="true" v-model="success" v-if="success">Post został zaktualizowany.</AlertSuccess> -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">Edytuj post</h2>
        </template>
        <div class="py-12">
            <div class="max-w-8xl sm:px-6 px-4 lg:px-8">
                <div class="bg-gray-100 shadow-xl rounded sm:rounded-lg px-6 sm:px-20 pt-16 pb-8 sm:pb-12">
                    <div class="flex flex-col sm:flex-row justify-between">
                        <h2 class="text-3xl text-gray-800 font-bold">
                            <i class="fa-regular fa-file-pen text-purple-600"></i>
                            Edytuj post
                        </h2>
                        <div class="mt-2">
                            <a :href="route('post.index')" class="text-blue-600 hover:text-blue-800 hover:cursor-pointer">
                                <i class="fa-solid fa-arrow-left mr-1"></i>
                                Wróć do wszystkich postów
                            </a>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    <InputLabel value="Tytuł"></InputLabel>
                    <TextInput
                        type="text"
                        class="mt-1 block w-full"
                        v-model="post.title"
                        placeholder="Tytuł..."
                    />                        
                    <InputError class="mt-2" :message="errors.title ? errors.title[0] : ''" />

                    <div class="flex flex-col sm:flex-row gap-6 mt-6">
                        <div class="grow">
                            <InputLabel class="mb-2" value="Początek publikacji"></InputLabel>
                            <VueDatePicker 
                                v-model="post.start_at" 
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
                                v-model="post.end_at" 
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
                        v-model="post.label_id"
                        :options="post_labels"
                        name_string="name"
                    ></SelectInput>
                    <InputError class="mt-2" :message="errors.label_id ? errors.label_id[0] : ''" />

                    <InputLabel class="mb-2 mt-6" value="Treść posta"></InputLabel>
                    
                    <div>
                        <QuillEditor theme="snow" toolbar="full" v-model:content="post.body" contentType="html"/>
                        <InputError class="mt-2" :message="errors.body ? errors.body[0] : ''" />
                    </div>

                    <div class="flex flex-row gap-2 my-2">
                        <label>
                            <Checkbox @change="toggle_type($event)" v-model:checked="checked"></Checkbox>
                            <span class="ml-2 text-sm text-gray-600">Szkic</span>
                        </label>
                    </div>


                    <SButton icon="<i class='fa-sharp fa-solid fa-floppy-disk-circle-arrow-right mr-2'></i>" class="mt-6" value="Aktualizuj post" @click="submit"></SButton>
                    
                </div>
            </div>
        </div>

    </AdminLayout>
</template>