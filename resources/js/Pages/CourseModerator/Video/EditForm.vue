<template>
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
                <v-btn text="Aktualizuj" @click="submit()" variant="outlined" color="#0284c7"
                    :disabled="form.processing"></v-btn>
                <v-btn text="Zamknij" @click="active = false" :disabled="form.processing"></v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>

import { ref, watch } from 'vue'
import TInput from '@/Composables/Form/TInput.vue'

import { AlertStore } from '@/Pinia/AlertStore'

const props = defineProps({
    video: {
        type: [Object, null],
        required: true
    }
})

const active = ref(true)
const errors = ref({})

const emits = defineEmits(['close'])

const alert_store = AlertStore()

watch(active, (value) => {
    if (!value) {
        emits('close')
    }
})

const form = ref({
    vimeo_video_id: props.video.vimeo_video_id,
    name: props.video.name,
    processing: false
})

const submit = async () => {
    form.value.processing = true
    errors.value = {}

    let response = await axios.patch(route('course_moderator.video.update', { id: props.video.id }), { ...form.value })
    response = response.data

    alert_store.pushAlert(response)

    if (response.errors) {
        errors.value = response.errors
        form.value.processing = false
    }

    if (response.success) {
        active.value = false
        emits('close', true)
    }
}

</script>
