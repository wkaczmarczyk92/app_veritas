<script setup>

import { use_processing_store } from '@/Pinia/ProcessingStore';
import { AlertStore } from '@/Pinia/AlertStore';
import { use_update_caretaker_recruiter_store } from '@/Pinia/Admin/UpdateCaretakerRecruiters';
import Spinner from '../Forms/Spinner.vue';
import { ref, watch } from 'vue'
import { format } from '../Functions/DateFormat.vue';
import { use_oral_exam_store } from '@/Pinia/OralExamStore';

// const update_caretaker_recruiter_store = use_update_caretaker_recruiter_store()
// const processing_store = use_processing_store()

const props = defineProps({
    by_user: {
        type: Boolean,
        default: false
    }
    // user_id: {
    //     type: [Number, null],
    //     required: true
    // }
})

const emits = defineEmits(['got_date'])

// console.log('dial;og', props.user_id)

// const open_dialog = ref(false)
const alert_store = AlertStore()
const oral_exam_store = use_oral_exam_store()
const processing_store = use_processing_store()

watch(
    () => oral_exam_store.open_dialog,
    (value) => {
        if (!value) {
            oral_exam_store.reset_date()
        }
    })

watch(
    () => oral_exam_store.exam_date.date,
    (value) => {
        oral_exam_store.exam_date.time = null
        oral_exam_store.get_todays_dates()
    }
)

const open = () => {
    if (oral_exam_store.selected_user_id === null && !props.by_user) {
        alert_store.pushAlert('danger', 'Wybierz użytkonika którego chcesz umówić na termin.')
        return;
    }

    oral_exam_store.open_dialog = true
}


const is_date_disabled = (date) => {
    const day = date.getDay()
    return day === 0 || day === 6
}

const maxDate = new Date()
maxDate.setMonth(maxDate.getMonth() + 2)
maxDate.setHours(23, 59, 59, 999)

const get_tile_class = (hour) => {
    let classes = 'tw-flex tw-justify-center tw-items-center tw-border tw-border-gray-400 tw-rounded-xl tw-transition tw-duration-300 tw-ease-in-out'

    if (oral_exam_store.taken_time.includes(hour)) {
        classes += ' tw-bg-red-600 tw-text-white tw-font-bold'
    } else {
        classes += ' hover:tw-bg-green-500 hover:tw-cursor-pointer hover:tw-text-white hover:tw-font-bold'
    }

    if (oral_exam_store.exam_date.time == hour) {
        classes += ' tw-bg-green-600 tw-text-white tw-font-bold tw-text-2xl'
    }

    classes += ' '

    return classes
}

</script>

<template>
    <v-btn :color="by_user ? '#9333ea' : '#2563eb'" size="x-large" @click="open()">
        <span v-if="!by_user">Wybierz termin</span>
        <span v-else>Wybierz termin egzaminu ustnego!</span>
        
    </v-btn>

    <v-dialog v-model="oral_exam_store.open_dialog" max-width="700">
        <template v-slot:default="{ isActive }">
            <Spinner v-if="processing_store.state" />
            <v-card title="Wybierz termin egzaminu">
                <v-card-text>
                    <div class="tw-flex tw-flex-col tw-gap-2">
                        <v-alert color="warning">Termin można wybrać maksymalnie 2 miesiące do przodu.</v-alert>
                        <VueDatePicker v-model="oral_exam_store.exam_date.date" :format="format" :preview="format"
                            :teleport="true" auto-apply :min-date="new Date()" :enable-time-picker="false"
                            :disabled-dates="is_date_disabled" :max-date="maxDate" />
                    </div>
                    <Transition mode="out-in" name="fade">
                        <div class="tw-grid tw-grid-cols-3 tw-gap-2 tw-mt-4" v-if="oral_exam_store.exam_date.date">
                            <div v-for="available_hour in oral_exam_store.available_hours"
                                @click.prevent="!oral_exam_store.taken_time.includes(available_hour) ? oral_exam_store.exam_date.time = available_hour : null"
                                :class="get_tile_class(available_hour)" class="">
                                <div class="tw-p-6 tw-flex tw-flex-col tw-text-center tw-items-center">
                                    <div>
                                        {{ available_hour }}:00
                                    </div>
                                    <div v-if="oral_exam_store.taken_time.includes(available_hour)">
                                        ZAJĘTE
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                    <Transition mode="out-in" name="fade">
                        <div v-if="oral_exam_store.exam_date.date && oral_exam_store.exam_date.time"
                            class="tw-flex tw-flex-col tw-gap-2 tw-mt-8">
                            <div class="tw-text-xl tw-text-center">
                                Wybrana data i godzina testu: <span class="tw-font-bold">{{
                                    format(oral_exam_store.exam_date.date)
                                }}, {{ oral_exam_store.exam_date.time }}:00</span>
                            </div>
                            <v-btn class="tw-mt-4" color="#2563eb" @click="oral_exam_store.store()">Potwierdź
                                termin</v-btn>
                        </div>
                    </Transition>
                </v-card-text>
                <!-- <v-card-text>
                    <Spinner v-if="processing_store.state" msg="Aktualizowanie rekruterów" />
                    <Transition mode="out-in" name="fade">
                        <div>Na pewno chcesz uruchomić aktualizację rekruterów przypisanych do opiekunek? Proces może potrwać nawet 10min! Podczas tego czasu NIE WOLNO wyłączać tego okna ani okna przeglądarki.</div>
                    </Transition>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Potwierdź" color="#2563eb" variant="outlined" @click="update_caretaker_recruiter_store.submit()"></v-btn>
                    <v-btn text="Anuluj" color="#dc2626" @click="isActive.value = false"></v-btn>
                </v-card-actions> -->
            </v-card>
        </template>
    </v-dialog>
</template>