<script setup>

import { ref } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import Spinner from '@/Components/Forms/Spinner.vue'

const props = defineProps({
    lesson: {
        type: Object,
        required: true
    }
})

const emits = defineEmits([
    'update-test'
])

const form = ref({
    file: []
})

const alert_store = AlertStore()
const processing = ref(false)

const submit = async () => {
    processing.value = true

    try {
        let form_data = new FormData()
        form_data.append('test', form.value.file[0])
        form_data.append('lesson_id', props.lesson.id)

        let response = await axios.post(route('german.tests.upload.from.file'), form_data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        response = response.data

        alert_store.pushAlert(response)

        if (response.success) {
            form.value.file = []
            emits('update-test', response.test)
        }
    } catch (error) {
        console.log(error)
        alert_store.exception()
    } finally {
        processing.value = false
    }
}

</script>

<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" color="#2563eb" text="Dodaj test" variant="flat"></v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Dodaj test">
                <Spinner v-if="processing" />
                <v-card-text>
                    <div class="tw-mb-4 tw-font-bold tw-text-red-600">Tylko pliki XML!
                    </div>
                    <v-file-upload clearable density="default" browse-text="Wybierz pliki" divider-text="lub"
                        title="Upuść pliki tutaj" v-model="form.file"></v-file-upload>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn color="#16a34a" variant="flat" text="Załaduj test" @click="submit()"></v-btn>
                    <v-btn text="Zamknij" @click="isActive.value = false"></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>