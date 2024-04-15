<template>
    <v-btn @click="active = true; init()" color="#0284c7" variant="tonal" size="large">
        <i class="fa-light fa-floppy-disk-circle-arrow-right tw-mr-2"></i>
        Dodaj webinarium
    </v-btn>
    <v-dialog max-width="500" v-model="active">
        <v-card title="Dodaj nowe webinarium" :loading="form.processing">
            <v-card-text>
                <TInput label="Vimeo ID" placeholder="wpisz id filmu z vimeo..."
                    :error="errors.vimeo_video_id ? errors.vimeo_video_id[0] : null"
                    v-model:model_value="form.vimeo_video_id">
                </TInput>
                <TInput class="mt-2" label="Nazwa webinarium" placeholder="wpisz nazwę webinarium..." type="text"
                    :error="errors.name ? errors.name[0] : null" v-model:model_value="form.name">
                </TInput>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text="Dodaj" @click="submit()" variant="outlined" color="#16a34a"
                    :disabled="form.processing"></v-btn>
                <v-btn text="Zamknij" @click="active = false" :disabled="form.processing"></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>

import TInput from '@/Composables/Form/TInput.vue'
import { AlertStore } from '@/Pinia/AlertStore';
import axios from 'axios';

import { ref } from 'vue'

const emit = defineEmits(['new-video'])

const alert_store = AlertStore()

const active = ref(false)

const form_init = () => {
    return {
        vimeo_video_id: '',
        name: '',
        processing: false
    }
}

const form = ref(form_init())

const init = () => {
    form.value = form_init()
    errors.value = {}
    form.value.processing = false
}

const errors = ref({})

const submit = async () => {
    form.value.processing = true
    errors.value = {}

    let response = await axios.post(route('course_moderator.video.store'), { ...form.value })
    response = response.data

    if (response.errors) {
        errors.value = response.errors
        form.value.processing = false
        return
    }

    if (response.success) {
        alert_store.pushAlert(response.alert_type, response.msg)
        emit('new-video', response.video)
        active.value = false
        form.value = form_init()
    }

    form.value.processing = false
}

</script>
