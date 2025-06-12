<script setup>

// import Spinner from '@/Composables/Spinner.vue'
import Spinner from '@/Components/Forms/Spinner.vue'
import { ref } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

const props = defineProps({
    question: {
        type: Object,
        required: true
    }
})

const form = ref({
    file: []
})

const processing = ref(false)

const emits = defineEmits([
    'update-file'
])

const alert_store = AlertStore()

const submit = async () => {
    processing.value = true
    let form_data = new FormData()
    form_data.append('audio_file', form.value.file[0])
    form_data.append('question_id', props.question.id)

    try {
        let response = await axios.post(route('questions.upload.audio'), form_data, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })

        response = response.data
        alert_store.pushAlert(response)

        if (response.success) {
            emits('update-file', response.file)
        }
    } catch (error) {
        console.error(error)
    } finally {
        processing.value = false
    }
}

</script>

<template>
    <v-dialog max-width="500">
        <template v-slot:activator="{ props: activatorProps }">
            <i v-bind="activatorProps" class="fas fa-upload hover:tw-cursor-pointer hover:tw-text-blue-600"></i>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Wrzuć plik audio" class="!tw-relative">
                <Spinner v-if="processing" />
                <v-card-text>

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