import { defineStore } from 'pinia';
import axios from 'axios';
import { AlertStore } from './AlertStore';

export const useTestStore = defineStore('test_store', {
    state: () => {
        return {
            test_id: null,
            test: [],
            questions: [],
            step: 0,
            e1: null,
            answers: [],
            result: null,
            is_passed: null,
            processing: false,
            alert_store: AlertStore()
        }
    },
    actions: {
        init(questions, test_id = null) {
            this.set_questions(questions)
            this.set_e1()
            this.set_test_id(test_id)

            this.test = []

            this.questions.forEach(question => {
                this.test.push({
                    id: question.id,
                    model: ''
                })
            })
        },
        set_questions(questions) {
            if (questions.length == 0) {
                throw new Error('Brak pytań')
            } else {
                this.questions = questions
            }
        },
        set_e1() {
            if (this.questions.length == 0 || !this.questions[0]) {
                throw new Error('Brak danych.')
            } else {
                this.e1 = this.questions[0].id
            }
        },
        set_test_id(id) {
            this.test_id = id
        },
        async handle_next(next) {
            let current_element = this.test.filter(item => item.id == this.e1)[0]

            if (current_element.model == '') {
                alert('Wybierz odpowiedź aby przejść dalej.')
            } else {
                if (!this.answers.filter(item => item.question_id == current_element.id).length > 0) {
                    this.answers.push({
                        question_id: current_element.id,
                        answer_id: current_element.model
                    })
                }

                if (this.answers.length == this.questions.length) {
                    await this.submit();
                } else {
                    next()
                }
            }
        },
        async submit() {
            this.processing = true

            try {
                let response = await axios.post(route('german.test.check-result'), {
                    test_id: this.test_id,
                    test_answer: this.answers
                })

                response = response.data

                if (response.success) {
                    this.is_passed = response.passed
                    this.result = response.good_answers_percent
                }

            } catch (error) {
                console.log(error)
                this.alert_store.exception()
            } finally {
                this.processing = false
            }
        },
        reset() {
            this.init(this.questions, this.test_id)
            this.step = 0
            this.set_e1()
            this.answers = []
            this.result = null
            this.is_passed = null
        }
    }
});
