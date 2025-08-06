<script setup>

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'
import Flex212 from '@/Templates/HTML/Data/Flex212.vue';
import Spinner from '@/Components/Forms/Spinner.vue'
import { useTestStore } from '@/Pinia/TestStore'
import { timestamp } from '@vueuse/core';
import DialogTestRules from './Templates/DialogTestRules.vue'

const props = defineProps({
    test_name: {
        type: String,
        default: 'Główny test'
    },
    test_id: {
        type: [Number, null],
        default: null
    },
    questions: {
        type: Object,
        required: true
    },
    is_admin: {
        type: Boolean,
        default: false
    },
    german_lesson: {
        type: Object,
        requried: true
    },
})

defineEmits(['close-dialog'])

const test_store = useTestStore()
test_store.init(props.questions, props.test_id)


</script>

<template>
    <Transition mode="out-in" name="fade">
        <div v-if="!test_store.start">
            <v-card>
                <v-card-text class="!tw-p-20">
                    <div class="tw-flex tw-flex-col tw-justify-center tw-items-center">
                        <div class="tw-text-2xl tw-mb-8">Czas na wykonanie testu: <span class="tw-font-bold">{{
                            test_store.time_to_minutes(german_lesson.test_time, true) }} min</span>
                        </div>
                        <DialogTestRules />
                        <!-- <v-btn size="x-large" color="#ea580c"
                            @click="test_store.start_test(german_lesson.test_time)">Zasady testu</v-btn> -->
                        <v-btn size="x-large" color="#2563eb" class="tw-mt-4"
                            @click="test_store.start_test(german_lesson.test_time)">Rozpocznij test</v-btn>
                    </div>
                </v-card-text>
            </v-card>
        </div>
        <v-card v-else title="Rozwiąż test">
            <v-card-text>
                <Flex212 v-if="is_admin" title="Nazwa" :value="props.test_name"></Flex212>
                <Flex212 v-if="is_admin" title="Czas" :value="test_store.current_time"></Flex212>
                <div class="tw-flex tw-flex-col tw-justify-center tw-items-center tw-my-6 tw-gap-4">
                    <div class="tw-text-2xl">
                        Pozostały czas
                    </div>
                    <div class="tw-text-3xl tw-font-bold">
                        {{ test_store.current_time }}
                    </div>
                </div>

                <div class="tw-flex tw-flex-col tw-gap-2 tw-mt-4">
                    <Spinner v-if="test_store.processing" msg="Sprwadzanie testu" />
                    <Transition mode="out-in">
                        <v-stepper v-model="test_store.e1" :non-linear="false" v-if="!test_store.result">
                            <template v-slot:default="{ next }">
                                <v-stepper-header>
                                    <template v-for="(n, index) in test_store.questions.map(q => q.id)"
                                        :key="`${n}-step`">
                                        <v-stepper-item :complete="test_store.e1 >= n" :value="n">
                                            <template v-slot:icon>
                                                {{ index + 1 }}
                                            </template>
                                        </v-stepper-item>
                                        <v-divider v-if="n !== test_store.questions.length" :key="n"></v-divider>
                                    </template>
                                </v-stepper-header>

                                <v-stepper-window>
                                    <v-stepper-window-item v-for="question in test_store.questions"
                                        :key="`${question.id}-content`" :value="question.id">
                                        <v-card color="">
                                            <v-card-text>
                                                <div class="tw-text-lg">{{ question.question }}</div>
                                                <div v-if="question.type.id == 5 && question.file[0]" class="tw-my-4">
                                                    <audio controls>
                                                        <source :src="`/lessons/${question.file[0].path}`">
                                                        Twoja przeglądarka nie wspiera plików audio.
                                                    </audio>
                                                </div>
                                                <v-radio-group
                                                    v-model="test_store.test.filter(item => item.id == question.id)[0].model">
                                                    <v-radio v-for="(answer, anwer_index) in question.closed_choices"
                                                        :key="answer.id" :label="answer.choice"
                                                        :value="answer.id"></v-radio>
                                                </v-radio-group>
                                            </v-card-text>
                                        </v-card>
                                    </v-stepper-window-item>
                                </v-stepper-window>

                                <v-stepper-actions>
                                    <template v-slot:prev></template>
                                    <template v-slot:next>
                                        <div class="tw-flex tw-flex-row tw-justify-end tw-w-full">
                                            <v-btn @click="test_store.handle_next(next)" color="#16a34a"
                                                :disabled="test_store.disable_next_question_button"
                                                variant="outlined">Następne
                                                pytanie</v-btn>
                                        </div>
                                    </template>

                                </v-stepper-actions>
                            </template>
                        </v-stepper>
                        <div v-else class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-2">
                            <v-icon size="64" color="success">mdi-check-circle</v-icon>
                            <!-- <div class="tw-text-lg">Test został zakończony.</div> -->
                            <div class="tw-text-lg">Twój wynik - {{ test_store.result }}%</div>
                            <div class="tw-text-lg tw-flex tw-flex-row tw-items-center tw-gap-2 tw-mt-4">
                                <i v-if="test_store.is_passed"
                                    class="fas fa-laugh-beam tw-text-3xl tw-text-green-600"></i>
                                <i v-else class="fas fa-frown tw-text-3xl tw-text-red-600"></i>
                                <div v-if="test_store.is_passed">Test zaliczony!</div>
                                <div v-else class="tw-font-bold">Test niezaliczony</div>
                            </div>
                            <div v-if="test_store.test_time == 0" class="tw-mt-4 tw-text-lg">Skończył się czas</div>


                        </div>
                    </Transition>
                </div>
                <slot></slot>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn v-if="is_admin || test_store.result == null" text="Spróbuj ponownie" @click="test_store.reset()"
                    color="#9333ea"></v-btn>
                <v-btn v-if="props.is_admin" text="Zamknij" @click="$emit('close-dialog')" color="red"></v-btn>
            </v-card-actions>
        </v-card>
    </Transition>
</template>