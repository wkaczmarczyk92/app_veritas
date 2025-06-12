<script setup>

import { upload_file } from '@/Composables/UploadFilesTinyMCE'
import Editor from '@tinymce/tinymce-vue'
import Loader from '@/Components/Loader.vue';
import Processing from './Processing.vue';
import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore';

import FileHandler from '@/Composables/Files/FileHandler';

const file_handler = new FileHandler();

const props = defineProps({
    modelValue: {
        type: [String, null],
        required: true

    }
})

const model = ref(props.modelValue)
const alert_store = AlertStore()
const processing = ref(false)

watch(props.modelValue, (value) => {
    model.value = value
})

watch(model, async (value) => {
    let prefix = '$2y$10$';

    if (value.includes(prefix)) {
        processing.value = true;

        try {
            let regex = /\[\$2y\$10\$[^\]]*\]/;
            let hash = value.match(regex);
            console.log(hash)

            if (hash) {
                hash = hash[0];
                console.log(hash)
                let response = await axios.post(route('hash.get'), {
                    hash: hash
                });

                response = response.data;

                if (response.success) {
                    if (response.file.mime_type) {
                        switch (file_handler.type(response.file)) {
                            case 'audio':
                                model.value = value = value.replace(hash, `<audio controls class="tw-z-10">
                                        <source src="${response.file.path}" type="${response.file.mime_type.type}">
                                    </audio>`);
                                break;
                            case 'video':
                                model.value = value = value.replace(hash, `<video class="tw-w-full tw-z-20" controls>
                                    <source src="${response.file.path}">
                                </video>`);
                                break;
                            case 'image':
                                model.value = value = value.replace(hash, `<p><img src="${response.file.path}" alt="" width="300" height="300" /></p>`);
                                break;
                            default:
                                alert_store.pushAlert('danger', 'Nieobsługiwany typ pliku.');
                                break;
                        }
                    } else {
                        alert_store.pushAlert('danger', 'Brak typu pliku. Skontaktuj się z administratorem.');
                    }
                }

                alert_store.pushAlert(response);
            } else {
                alert_store.pushAlert('danger', 'Nie wyodrębniono hashu. Spróbuj ponownie.');
            }
        } catch (error) {
            console.log(error);
            alert_store.exception()
        }
    }

    emit('update-value', value)
    processing.value = false;
})

const emit = defineEmits([
    'update-value'
])

</script>

<template>
    <Processing msg="Konwersja tekstu..." v-if="processing" />
    <Editor api-key="r2mcdb1fzo4fckqa7t15kwl0dog0cwn3sf22nwxidwty980a" v-model="model" :init="{
        height: 500,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount',
            'media'
        ],
        toolbar:
            'undo redo | formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat | help | image | media',
        file_picker_types: 'image',
        file_picker_callback: upload_file,
        image_title: true,
        automatic_uploads: true,
    }" />
</template>
