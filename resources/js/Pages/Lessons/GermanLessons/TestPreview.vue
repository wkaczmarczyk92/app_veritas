<script setup>

import { ref } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import DialogAddAudio from './DialogAddAudio.vue';
import Spinner from '@/Components/Forms/Spinner.vue';

const props = defineProps({
    lesson: {
        type: Object,
        required: true
    }
})

const alert_store = AlertStore()
console.log(props.lesson.test[0].questions)

const processing = ref(false)

const destroy_audio = async (question) => {
    if (confirm('Na pewno chcesz usunąć plik audio?')) {
        processing.value = true
        try {
            let response = await axios.delete(route('questions.audio.destroy', {
                file_id: question.file[0].id
            }))

            response = response.data
            alert_store.pushAlert(response)

            if (response.success) {
                question.file = []
            }
        } catch (error) {
            console.error(error)
        } finally {
            processing.value = false
        }
    }
}

</script>

<template>
    <v-dialog max-width="800">
        <template v-slot:activator="{ props: activatorProps }">
            <v-btn v-bind="activatorProps" color="surface-variant" text="Zobacz test" variant="flat"></v-btn>
        </template>

        <template v-slot:default="{ isActive }">
            <v-card title="Podgląd testu">
                <v-card-text>
                    <Flex212 title="Nazwa" :value="lesson.test[0].name"></Flex212>
                    <Flex212 title="Pytania"></Flex212>

                    <!-- {{ lesson.test[0].questions }} -->
                    <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-4">
                        <v-card v-for="(question, index) in lesson.test[0].questions" :key="question.id">
                            <v-card-text>
                                <Spinner v-if="processing" />
                                <div class="tw-flex tw-flex-row tw-gap-4">
                                    <div class="tw-text-lg">
                                        {{ question.question }}
                                        <!-- {{ question.file }} -->
                                    </div>
                                    <div v-if="question.type.id == 5" class="tw-text-2xl">
                                        <DialogAddAudio v-if="!question.file[0]" :question="question"
                                            @update-file="question.file.push($event)" />
                                        <i v-else
                                            class="fas fa-file-times hover:tw-cursor-pointer hover:tw-text-red-600"
                                            @click="destroy_audio(question)"></i>
                                    </div>
                                </div>
                                <div v-if="question.type.id == 5 && question.file[0]" class="tw-my-4">
                                    <audio controls>
                                        <source :src="`/lessons/${question.file[0].path}`">
                                        Twoja przeglądarka nie wspiera plików audio.
                                    </audio>
                                </div>
                                <ul class="tw-list-disc tw-px-4">
                                    <li v-for="(answer, key) in question.closed_choices" :key="answer.id"
                                        :class="Number(answer.is_correct) ? 'tw-text-green-600 tw-font-bold' : ''">
                                        <!-- {{ answer.is_correct }} -->
                                        {{ answer.choice }}
                                    </li>
                                </ul>
                            </v-card-text>
                        </v-card>
                    </div>
                    <!-- {{ lesson.test[0] }} -->
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn text="Zamknij" @click="isActive.value = false"></v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>