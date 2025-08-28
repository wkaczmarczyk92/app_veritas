import { defineStore } from 'pinia';
import { shallowRef } from 'vue';
import NewAlertSuccess from '../Components/Alerts/NewAlertSuccess.vue';
import NewAlertDanger from '../Components/Alerts/NewAlertDanger.vue';
import { try_catch } from '@/Composables/TryCatch';
import { use_processing_store } from './ProcessingStore';
import { AlertStore } from './AlertStore';
import { format } from '@/Components/Functions/DateFormat.vue';
import { useTestStore } from './TestStore';

export const use_oral_exam_store = defineStore('oral_exam_store', {
    state: () => {
        return {
            available_users: [],
            exam_date: {
                time: null,
                date: null
            },
            selected: [],
            selected_date: [],
            taken_dates: [],
            available_hours: [
                10, 11, 12, 13, 14
            ],
            processing_store: use_processing_store(),
            alert_store: AlertStore(),
            exams: {},
            open_dialog: false,
            selected_user_id: null,
            taken_time: [],
            test_store: useTestStore(),
            has_any_oral_exam: false,
            oral_exam_passed: false,
            oral_exam_signed_up: false,
            current_oral_exam: null
        }
    },
    actions: {
        set_user_settings(oral_exam_passed, has_any_oral_exam) {
            this.has_any_oral_exam = has_any_oral_exam
            this.oral_exam_passed = oral_exam_passed
        },
        set_users(users) {
            this.available_users = users
        },
        init(exams) {
            this.exams = exams
        },
        async get_todays_dates() {
            try_catch(async () => {
                let response = await axios.post(route('oral.exam.download.selected.date'), {
                    date: format(this.exam_date.date),
                    time: this.exam_date.time
                })

                response = response.data
                console.log('terminy', response)

                if (response.success) {
                    this.taken_time = response.taken_time
                    console.log('taken days', this.taken_time)
                }
            }, this)
        },
        async store() {
            try_catch(async () => {
                let response = await axios.post(route('oral.exam.store'), {
                    user_id: this.selected_user_id,
                    date: format(this.exam_date.date),
                    time: this.exam_date.time
                })

                response = response.data
                console.log('zapis terminu', response)

                this.alert_store.pushAlert(response)

                if (response.success) {
                    this.available_users = this.available_users.filter(user => user.id != response.oral_exam.user_id)
                    this.reset_date()
                    this.open_dialog = false

                    this.exams = response.oral_exams
                    this.current_oral_exam = response.oral_exam
                    this.test_store.oral_exam_set = true
                    this.has_any_oral_exam = true
                }
            }, this)
        },
        reset_selected() {
            this.selected = []
        },
        reset_date() {
            this.exam_date.time = null
            this.exam_date.date = null
            this.selected_user_id = null
        },
        async destroy() {
            try_catch(async () => {
                let response = await axios.delete(route('oral.exam.destroy', {
                    ids: this.selected
                }))

                response = response.data

                this.exams = this.exams.filter(exam => !this.selected.includes(exam.id))
                 
                this.alert_store.pushAlert(response)

                if (response.success) {
                    this.available_users = response.users
                }
            }, this)
        },
        async set_as_passed(id) {
            try_catch(async () => {
                let response = await axios.post(route('oral.exam.set.as.passed'), {
                    id: id
                })
                response = response.data

                this.alert_store.pushAlert(response)

                if (response.success) {
                    this.exams = this.exams.filter(exam => exam.id != id)
                    this.reset_selected()
                }
            }, this)
        },
        async set_as_failure(id) {
            try_catch(async () => {
                let response = await axios.post(route('oral.exam.set.as.failure'), {
                    id: id
                })
                response = response.data

                this.alert_store.pushAlert(response)

                if (response.success) {
                    this.exams = this.exams.filter(exam => exam.id != id)
                    this.available_users = response.users
                    this.reset_selected()
                }
            }, this)
        },
    }
});