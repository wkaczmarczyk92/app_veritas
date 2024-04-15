<script setup>

import { Head, router } from '@inertiajs/vue3'

import CourseModeratorLayout from '@/Layouts/CourseModeratorLayout.vue'
import MainContent from '@/Templates/HTML/MainContent.vue'

import { ref, watch } from 'vue'
import { AlertStore } from '@/Pinia/AlertStore'

import TInput from '@/Composables/Form/TInput.vue'
import Editor from '@tinymce/tinymce-vue'
import InputError from '@/Components/InputError.vue'

// import Preview from './Preview.vue'

import { upload_file } from '@/Composables/UploadFilesTinyMCE'

import MCEEditor from '@/Composables/MCEEditor.vue'

import Processing from '@/Composables/Processing.vue'

// import AnswerChip from '@/Composables/Chips/AnswerChip.vue'

import AnswerChip from '@/Composables/Chips/AnswerChip.vue'
import CorrectAnswerChip from '@/Composables/Chips/CorrectAnswerChip.vue'
import MatchChip from '@/Composables/Chips/MatchChip.vue'
import QuestionTypeChip from '@/Composables/Chips/QuestionTypeChip.vue'

const props = defineProps({
    roles: {
        type: Object,
        required: true
    },
    company_branches: {
        type: Object,
        required: true
    },
    merged: {
        type: Object,
        required: true
    },
    question_types: {
        type: Object,
        required: true
    },
    visibility: {
        type: Object,
        required: true
    }
})

const breadcrumbs = [
    {
        title: 'VeritasApp',
        disabled: false,
        href: route('course_moderator.dashboard')
    },
    {
        title: 'Testy',
        disabled: false,
        href: route('course_moderator.test.index')
    },
    {
        title: 'Dodaj test',
        disabled: true
    }
]

const form_init = () => {
    return {
        name: '',
        time: null,
        class_and_id: null,
        permissions: {
            roles: [],
            company_branches: []
        },
        visibility_id: null,
        // test: {
        //     questions: [],
        // },
        questions: []
    }
}

const current_question_type = ref(null)
const current_question = ref(null)

const question_open = ref({

})

const alert_store = AlertStore()

watch(() => current_question_type.value, (new_value, old_value) => {
    console.log('zmiana typu pytania', new_value, old_value)

    if (new_value != old_value && current_question.value != null) {
        current_question.value = null
    }


    console.log(current_question.value)

})

const form = ref(form_init())
const get_question_type = (type_name) => {
    return props.question_types.filter(q => q.type === type_name)[0].id
}

const add_question = () => {
    if (current_question_type.value === null) {
        alert_store.pushAlert('danger', 'Wybierz typ pytania!')
        return
    }

    switch (current_question_type.value) {
        case 'open':
            current_question.value = {
                type_id: get_question_type('open'),
                type: 'open',
                question: '',
                edit: false
            }

            console.log(current_question.value)
            break
        case 'closed':
            current_question.value = {
                type_id: get_question_type('closed'),
                type: 'closed',
                question: '',
                edit: false,
                correct_answer: null,
                choices: [
                    {
                        is_correct: false,
                        answer: ''
                    }
                ]
            }

            console.log(current_question.value)
            break
        case 'closed_multiple':
            current_question.value = {
                type_id: get_question_type('closed_multiple'),
                type: 'closed_multiple',
                question: '',
                edit: false,
                choices: [
                    {
                        is_correct: false,
                        answer: ''
                    }
                ]
            }

            console.log(current_question.value)
            break
        case 'match':
            current_question.value = {
                type_id: get_question_type('match'),
                type: 'match',
                question: '',
                edit: false,
                matches: [
                    {
                        match_text: '',
                        match_file_id: '',
                        answer: ''
                    }
                ]
            }

            console.log(current_question.value)
            break
    }
}

const add_question_to_test = () => {
    switch (current_question_type.value) {
        case 'open':
            if (current_question.value.question === '') {
                alert_store.pushAlert('danger', 'Treść pytania nie może być pusta!')
                return
            }

            if (current_question.value.edit === false) {
                form.value.questions.push(current_question.value)
                alert_store.pushAlert('success', 'Pytanie dodane!')
            } else {
                form.value.questions[current_question.value.edit] = current_question.value
                form.value.questions[current_question.value.edit].edit = false
                alert_store.pushAlert('success', 'Pytanie zaktualizowane!')
            }

            current_question.value = null
            current_question_type.value = null
            break
        case 'closed':
            if (current_question.value.question === '') {
                alert_store.pushAlert('danger', 'Treść pytania nie może być pusta!')
                return
            }

            if (current_question.value.choices.length < 2) {
                alert_store.pushAlert('danger', 'Pytanie musi mieć przynajmniej dwie odpowiedzi!')
                return
            }

            if (current_question.value.choices.filter(c => c.answer.trim() === '').length > 0) {
                alert_store.pushAlert('danger', 'Wszystkie odpowiedzi muszą być wypełnione!')
                return
            }

            if (current_question.value.correct_answer === null) {
                alert_store.pushAlert('danger', 'Wybierz poprawną odpowiedź!')
                return
            }

            if (current_question.value.edit === false) {
                current_question.value.choices[current_question.value.correct_answer].is_correct = true
                form.value.questions.push(current_question.value)
                alert_store.pushAlert('success', 'Pytanie dodane!')
            } else {
                form.value.questions[current_question.value.edit] = current_question.value
                form.value.questions[current_question.value.edit].edit = false
                alert_store.pushAlert('success', 'Pytanie zaktualizowane!')
            }

            current_question.value = null
            current_question_type.value = null

            console.log(form.value.questions)
            break
        case 'closed_multiple':
            if (current_question.value.question === '') {
                alert_store.pushAlert('danger', 'Treść pytania nie może być pusta!')
                return
            }

            if (current_question.value.choices.length < 2) {
                alert_store.pushAlert('danger', 'Pytanie musi mieć przynajmniej dwie odpowiedzi!')
                return
            }

            if (current_question.value.choices.filter(c => c.answer === '').length > 0) {
                alert_store.pushAlert('danger', 'Wszystkie odpowiedzi muszą być wypełnione!')
                return
            }

            if (current_question.value.choices.filter(c => c.is_correct === true).length === 0) {
                alert_store.pushAlert('danger', 'Wybierz poprawną odpowiedź!')
                return
            }

            if (current_question.value.edit === false) {
                form.value.questions.push(current_question.value)
                alert_store.pushAlert('success', 'Pytanie dodane!')
            } else {
                form.value.questions[current_question.value.edit] = current_question.value
                form.value.questions[current_question.value.edit].edit = false
                alert_store.pushAlert('success', 'Pytanie zaktualizowane!')
            }

            current_question.value = null
            current_question_type.value = null

            console.log(form.value.questions)
            break
        case 'match':
            if (current_question.value.question === '') {
                alert_store.pushAlert('danger', 'Treść pytania nie może być pusta!')
                return
            }

            if (current_question.value.matches.length < 2) {
                alert_store.pushAlert('danger', 'Pytanie musi mieć przynajmniej dwa dopasowania!')
                return
            }

            if (current_question.value.matches.filter(m => m.match_text === '' && m.match_file_id === '').length > 0) {
                alert_store.pushAlert('danger', 'Wszystkie dopasowania muszą być wypełnione!')
                return
            }

            if (current_question.value.matches.filter(m => m.answer === '').length > 0) {
                alert_store.pushAlert('danger', 'Wszystkie odpowiedzi muszą być wypełnione!')
                return
            }

            if (current_question.value.edit === false) {
                form.value.questions.push(current_question.value)
                alert_store.pushAlert('success', 'Pytanie dodane!')
            } else {
                form.value.questions[current_question.value.edit] = current_question.value
                form.value.questions[current_question.value.edit].edit = false
                alert_store.pushAlert('success', 'Pytanie zaktualizowane!')
            }

            current_question.value = null
            current_question_type.value = null

            break
    }


    console.log(form.value.questions)
}

const remove_question = (index) => {
    form.value.questions.splice(index, 1)

    console.log(form.value.questions)

    if (current_question.value.edit == index) {
        current_question.value = null
        current_question_type.value = null
    }
}

const edit_question = async (index) => {
    current_question_type.value = form.value.questions[index].type
    await sleep(1)
    current_question.value = form.value.questions[index]

    current_question.value.edit = index

    if (current_question.value.type === 'closed') {
        current_question.value.correct_answer = current_question.value.choices.findIndex(c => c.is_correct === true)
    }

    console.log('current question', current_question.value)
    console.log('current question type', current_question_type.value)
}

const errors = ref({})
const processing = ref(false)

const add_test = async () => {
    errors.value = {}
    processing.value = true

    let response = await axios.post(route('course_moderator.test.store'), { ...form.value })
    response = response.data

    if (response.errors) {
        errors.value = response.errors
    }

    if (response.success) {
        alert_store.pushAlert(response)
        form.value = form_init()
    }

    console.log(response)
    processing.value = false
}

const add_closed_choice = () => {
    current_question.value.choices.push({
        is_correct: false,
        answer: ''
    })
}

const sleep = ms => new Promise(r => setTimeout(r, ms));

const remove_closed_choice = async (index) => {
    console.log('przed', current_question.value)
    if (current_question.value.choices.length == 1) {
        alert_store.pushAlert('danger', 'Pytanie musi mieć przynajmniej jedną odpowiedź!')
        await sleep(1)
        if (typeof current_question.value.correct_answer !== 'undefined') {
            console.log('tutaj o 11')
            current_question.value.correct_answer = null
        } else {
            console.log('tutaj o 22')
            current_question.value.choices.forEach(c => c.is_correct = false)
        }

        console.log(current_question.value)
        return
    }

    current_question.value.choices.splice(index, 1)
    await sleep(1)

    if (typeof current_question.value.correct_answer !== 'undefined') {
        console.log('tutaj o 1')
        current_question.value.correct_answer = null
    } else {
        console.log('tutaj o 2')
        current_question.value.choices.forEach(c => c.is_correct = false)
    }

    console.log(current_question.value)
}

const add_matches = () => {
    current_question.value.matches.push({
        match_text: '',
        match_file_id: '',
        answer: ''
    })
}

const remove_match = (index) => {
    if (current_question.value.matches.length == 1) {
        alert_store.pushAlert('danger', 'Pytanie musi mieć przynajmniej jedną parę!')
        return
    }

    current_question.value.matches.splice(index, 1)
}

// const question_type_changed = async () => {
//     console.log('zmiana typu pytania')
//     await sleep(1)
//     current_question.value = null

//     console.log('current question', current_question_type.value)

// }

</script>


<template>

    <Head title="VeritasApp - moderator kursów - dodaj test" />
    <CourseModeratorLayout>
        <template #header>
            <v-breadcrumbs :items="breadcrumbs">
                <template v-slot:divider>
                    <i class="fa-solid fa-chevron-right"></i>
                </template>
            </v-breadcrumbs>
        </template>

        <MainContent>
            <div class="tw-grid lg:tw-grid-cols-10 tw-grid-cols-1 tw-gap-4 tw-relative">
                <Processing v-if="processing" msg="Dodawanie testu" />
                <v-card :loading="false" title="Kreator pytań" class="!tw-p-4 tw-col-span-4">
                    <template v-slot:prepend>
                        <i class="fa-regular fa-file-circle-plus tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">

                        <v-select clearable label="Wybierz rodzaj pytania" :items="question_types" item-title="name_pl"
                            item-value="type" variant="outlined" hide-details
                            v-model="current_question_type"></v-select>
                        <div class="tw-flex tw-flex-row">
                            <v-btn variant="outlined" color="#16a34a" size="large" @click="add_question()"
                                :disabled="false">
                                <i class="fa-solid fa-plus tw-mr-2"></i>
                                Dodaj pytanie
                            </v-btn>
                        </div>
                        <div v-if="current_question" class="tw-mt-8">
                            <!-- pytanie otwarte -->
                            <div v-if="current_question_type == 'open'">
                                <h2 class="tw-text-base tw-mb-1">Pytanie otwarte</h2>
                                <TInput v-model:model_value="current_question.question" placeholder="treść pytania..."
                                    @keyup.enter="add_question_to_test()">
                                </TInput>
                            </div>

                            <!-- pytanie zamkniete - jednokrotny wybór -->
                            <div v-if="current_question_type == 'closed'">
                                <h2 class="tw-text-base tw-mb-1">Pytanie zamknięte - jednokrotny wybór</h2>
                                <TInput v-model:model_value="current_question.question" placeholder="treść pytania...">
                                </TInput>
                                <v-radio-group v-model="current_question.correct_answer" class="tw-mt-4">
                                    <v-radio v-for="(choice, index) in current_question.choices" class="tw-mb-2"
                                        :value="index">
                                        <template v-slot:label>
                                            <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-w-full">
                                                <TInput v-model:model_value="choice.answer"
                                                    placeholder="treść odpowiedzi...">
                                                    <template v-slot:append>
                                                        <i class="fa-solid fa-xmark tw-text-red-600 hover:tw-cursor-pointer hover:tw-text-red-700 tw-text-2xl"
                                                            @click="remove_closed_choice(index)"></i>
                                                    </template>
                                                </TInput>
                                            </div>
                                        </template>
                                    </v-radio>
                                </v-radio-group>
                                <v-btn variant="outlined" color="#9333ea" size="small" @click="add_closed_choice()"
                                    :disabled="false">
                                    <i class="fa-solid fa-plus tw-mr-2"></i>
                                    Dodaj wybór
                                </v-btn>
                            </div>

                            <!-- pytanie zamknięte - wielokrotny wybór -->
                            <div v-if="current_question_type == 'closed_multiple'">
                                <h2 class="tw-text-base tw-mb-1">Pytanie zamknięte - wielokrotny wybór</h2>
                                <TInput v-model:model_value="current_question.question" placeholder="treść pytania..."
                                    class="tw-mb-4" />
                                <v-checkbox v-for="(choice, index) in current_question.choices" hide-details
                                    v-model="choice.is_correct" class="tw-mt-2">
                                    <template v-slot:label>
                                        <div class="tw-flex tw-flex-row tw-gap-2 tw-items-center tw-w-full">
                                            <TInput v-model:model_value="choice.answer"
                                                placeholder="treść odpowiedzi...">
                                                <template v-slot:append>
                                                    <i class="fa-solid fa-xmark tw-text-red-600 hover:tw-cursor-pointer hover:tw-text-red-700 tw-text-2xl"
                                                        @click="remove_closed_choice(index)"></i>
                                                </template>
                                            </TInput>
                                        </div>
                                    </template>
                                </v-checkbox>
                                <v-btn variant="outlined" color="#9333ea" size="small" @click="add_closed_choice()"
                                    :disabled="false" class="tw-mt-4">
                                    <i class="fa-solid fa-plus tw-mr-2"></i>
                                    Dodaj wybór
                                </v-btn>
                            </div>

                            <!-- pytanie dopasowania -->
                            <div v-if="current_question_type == 'match'">
                                <h2 class="tw-text-base tw-mb-1">Pytanie dopasowania</h2>
                                <TInput v-model:model_value="current_question.question" placeholder="treść pytania..."
                                    class="tw-mb-4" />

                                <div v-for="(match, index) in current_question.matches"
                                    class="tw-grid tw-grid-cols-2 tw-gap-2 tw-items-center tw-mb-2">
                                    <TInput v-model:model_value="match.match_text" placeholder=""></TInput>
                                    <TInput v-model:model_value="match.answer" placeholder="odpowiedź">
                                        <template v-slot:append>
                                            <i class="fa-solid fa-xmark tw-text-red-600 hover:tw-cursor-pointer hover:tw-text-red-700 tw-text-2xl"
                                                @click="remove_match(index)"></i>
                                        </template>
                                    </TInput>
                                </div>

                                <v-btn variant="outlined" color="#9333ea" size="small" @click="add_matches()"
                                    :disabled="false" class="tw-mt-4">
                                    <i class="fa-solid fa-plus tw-mr-2"></i>
                                    Dodaj parę
                                </v-btn>
                            </div>
                            <div class="tw-mt-4">
                                <v-btn variant="outlined" color="#0284c7" size="large" @click="add_question_to_test()"
                                    :disabled="false">
                                    <i class="fa-sharp fa-regular fa-layer-plus tw-mr-2"></i>
                                    <span v-if="current_question.edit !== false">Aktualizuj pytanie</span>
                                    <span v-else>Dodaj pytanie do testu</span>
                                </v-btn>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>

                <v-card :loading="false" title="Pytania" class="!tw-p-4 tw-col-span-3">
                    <template v-slot:prepend>
                        <i class="fa-solid fa-clipboard-question tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">
                        <v-list lines="one">
                            <v-list-item v-for="(question, index) in form.questions" :key="index"
                                :title="(index + 1) + '. ' + question.question"
                                class="hover:tw-bg-gray-200 !tw-rounded-xl odd:tw-bg-gray-100 even:tw-border-gray-200 even:tw-border tw-mb-2 !tw-py-4">
                                <template v-slot:title>
                                    <div
                                        class="tw-flex tw-flex-row tw-justify-between tw-items-center tw-gap-2 tw-py-1">
                                        <div
                                            class="tw-flex tw-flex-row tw-items-center tw-justify-between tw-gap-2 tw-w-full">
                                            <span>{{ (index + 1) }}. {{ question.question }}</span>

                                        </div>
                                        <div class="tw-flex tw-flex-row tw-gap-1">
                                            <i class="fa-solid fa-pen-to-square tw-text-sky-600 hover:tw-text-sky-700 hover:tw-cursor-pointer"
                                                @click="edit_question(index)"></i>
                                            <i class="fa-solid fa-xmark tw-text-red-600 hover:tw-cursor-pointer hover:tw-text-red-700"
                                                @click="remove_question(index)"></i>
                                        </div>
                                    </div>
                                    <div class="tw-flex tw-flex-row tw-gap-2 tw-justify-right tw-items-center tw-mt-1">
                                        <QuestionTypeChip :question="question" :question_types="question_types" />
                                        <!-- <v-chip variant="outlined" class="" size="small">
                                            {{ question_types.filter(q => q.id === question.type_id)[0].name_pl
                                            }}
                                        </v-chip> -->
                                        <div class="tw-text-end"
                                            v-if="['closed', 'closed_multiple'].includes(question.type)">
                                            <AnswerChip :question="question" />
                                        </div>
                                        <div class="tw-text-end" v-if="['closed_multiple'].includes(question.type)">
                                            <CorrectAnswerChip :question="question" />
                                        </div>
                                        <div class="tw-text-end" v-if="['match'].includes(question.type)">
                                            <MatchChip :question="question" />
                                            <!-- <v-chip variant="outlined" class="" color="#9333ea" size="small">
                                                dopasowań: {{ question.matches.length
                                                }}
                                            </v-chip> -->
                                        </div>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
                <v-card :loading="false" title="Ustawienia testu" class="!tw-p-4 tw-col-span-3">
                    <template v-slot:prepend>
                        <i class="fa-duotone fa-gears tw-text-2xl"></i>
                    </template>
                    <v-card-text class="tw-flex tw-gap-4 tw-flex-col tw-my-6">

                        <h2 class="tw-text-base">Nazwa testu</h2>
                        <TInput v-model:model_value="form.name" placeholder="nazwa testu..." />
                        <InputError :message="errors.name ? errors.name[0] : null" />

                        <h2 class="tw-text-base">Czas na rozwiązanie testu (opcjonalnie)</h2>
                        <TInput v-model:model_value="form.time" placeholder="czas w minutach, np 5.30...">
                        </TInput>

                        <h2 class="tw-text-base">Przypisz do kompendium, vademecum lub lekcji (opcjonalnie)</h2>
                        <v-autocomplete label="Wybierz kompendium, vademecum lub lekcję" clearable variant="outlined"
                            class="autocomplete-input" :items="merged" no-data-text="Brak danych" item-title="name"
                            v-model="form.class_and_id" item-value="value"></v-autocomplete>


                        <h2 class="tw-text-base">Widoczność testu</h2>
                        <v-select :items="visibility" item-title="name_pl" item-value="id" label="Widoczność testu"
                            hide-details variant="outlined" v-model="form.visibility_id" />
                        <InputError :message="errors.visibility_id ? errors.visibility_id[0] : ''" class="tw-mt-1" />

                        <h2 class="tw-text-base">Test dostępny dla</h2>
                        <v-select clearable chips label="Wybierz role" :items="roles" multiple item-title="name_pl"
                            item-value="id" variant="outlined" hide-details v-model="form.permissions.roles"></v-select>

                        <v-select clearable chips label="Wybierz oddziały" :items="company_branches" multiple
                            item-title="name" item-value="id" variant="outlined" hide-details
                            v-model="form.permissions.company_branches"></v-select>

                        <InputError
                            :message="errors['empty_roles_and_company_branches'] ? errors['empty_roles_and_company_branches'][0] : null" />
                    </v-card-text>
                    <v-card-actions>
                        <v-btn variant="outlined" color="#0284c7" size="large" @click="add_test()" :disabled="false">
                            <i class="fa-solid fa-plus tw-mr-2"></i>
                            Dodaj test
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </div>
        </MainContent>
    </CourseModeratorLayout>
</template>

<style>
.autocomplete-input .v-field__input {
    padding-top: 0;
    padding-bottom: 0;
}

.v-list-item-title {
    white-space: unset !important;
}

.v-label {
    width: 100%;
}
</style>