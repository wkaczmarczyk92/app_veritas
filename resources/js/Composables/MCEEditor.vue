<script setup>

import { upload_file } from '@/Composables/UploadFilesTinyMCE'
import Editor from '@tinymce/tinymce-vue'

import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, null],
        required: true

    }
})

const model = ref(props.modelValue)

watch(props.modelValue, (value) => {
    model.value = value
})

watch(model, (value) => {
    emit('update-value', value)
})

const emit = defineEmits([
    'update-value'
])

</script>

<template>
    <Editor api-key="r2mcdb1fzo4fckqa7t15kwl0dog0cwn3sf22nwxidwty980a" v-model="model" :init="{
        height: 500,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar:
            'undo redo | formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat | help | image',
        file_picker_types: 'image',
        file_picker_callback: upload_file,
        image_title: true,
        automatic_uploads: true,
    }" />
</template>
