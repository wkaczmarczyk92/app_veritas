import { defineStore } from "pinia";
import axios from "axios";
import { try_catch } from "@/Composables/TryCatch";
import { AlertStore } from "./AlertStore";

export const useTestStore = defineStore("test_store", {
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
            alert_store: AlertStore(),
            start: false,
            test_time: null,
            current_time: null,
            timer_interval: null,
            test_attempts: null,
            disable_next_question_button: false,
            oral_exam_set: false
        };
    },
    actions: {
        start_test(time) {
            this.start = true;
            this.test_time = time;
            this.time_to_minutes(time);
            this.start_timer();
        },
        time_to_minutes(time, back = false) {
            let minutes = Math.trunc(time / 60);
            let seconds = time - minutes * 60;
            seconds = Number(seconds) <= 9 ? `0${seconds}` : seconds;

            if (!back) {
                console.log('back')
                this.current_time = `${minutes}:${seconds}`;
            } else {
                console.log('not back')
                // seconds = Number(seconds) <= 9 ? `0${seconds}` : seconds;
                console.log("sekundy", seconds);
                return `${minutes}:${seconds}`;
            }
        },
        async start_timer() {
            this.timer_interval = setInterval(() => {
                if (this.test_time <= 1) {
                    this.test_time = 0;
                    this.time_to_minutes(this.test_time);
                    clearInterval(this.timer_interval);

                    setTimeout(async () => {
                        await this.submit();
                    }, 100);
                } else {
                    --this.test_time;
                    this.time_to_minutes(this.test_time);
                }
            }, 1000);
        },
        set_test_attempts(test_attempts) {
            this.test_attempts = Number(test_attempts);
            console.log('testattemptsinit', this.test_attempts)
        },
        init(questions, test_id = null) {
            console.log("init questions", questions);
            this.set_questions(questions);
            this.set_e1();
            this.set_test_id(test_id);

            this.disable_next_question_button = false;

            this.test = [];

            this.questions.forEach((question) => {
                this.test.push({
                    id: question.id,
                    model: "",
                });
            });
        },
        set_questions(questions) {
            console.log("store questions", questions);
            if (questions.length == 0) {
                throw new Error("Brak pytań");
            } else {
                this.questions = questions;
            }
        },
        set_e1() {
            if (this.questions.length == 0 || !this.questions[0]) {
                throw new Error("Brak danych.");
            } else {
                this.e1 = this.questions[0].id;
            }
        },
        set_test_id(id) {
            this.test_id = id;
        },
        async handle_next(next) {
            this.disable_next_question_button = true;
            
            let current_element = this.test.filter(
                (item) => item.id == this.e1
            )[0];

            if (current_element.model == "") {
                alert("Wybierz odpowiedź aby przejść dalej.");
            } else {
                if (
                    !this.answers.filter(
                        (item) => item.question_id == current_element.id
                    ).length > 0
                ) {
                    this.answers.push({
                        question_id: current_element.id,
                        answer_id: current_element.model,
                    });
                }

                if (this.answers.length == this.questions.length) {
                    await this.submit();
                } else {
                    next();
                    this.disable_next_question_button = false;
                }
            }
        },
        async reload_questions() {
            this.processing = true;

            try {
                let response = await axios.post(route('german.test.questions'))
                response = response.data

                this.set_questions(response.questions) 

                
            } catch (error) {
                console.log(error)
                this.alert_store.exception()
            } finally {
                this.processing = false
            }
        },
        async submit() {
            this.processing = true;

            if (this.timer_interval) {
                clearInterval(this.timer_interval);
                this.timer_interval = null;
            }

            try {
                let response = await axios.post(
                    route("german.test.check-result"),
                {
                        test_id: this.test_id,
                        test_answer: this.answers,
                        test: this.test,
                    }
                );

                response = response.data;

                if (response.success) {
                    this.is_passed = response.passed;
                    this.result = response.good_answers_percent;
                    ++this.test_attempts;

                    console.log("test_attempts", this.test_attempts);
                }
            } catch (error) {
                console.log(error);
                this.alert_store.exception();
            } finally {
                this.processing = false;
            }
        },
        async reset(full_reset = true) {
            if (full_reset) {
                await this.reload_questions()
                this.init(this.questions, this.test_id);
                this.set_e1();
            }

            this.step = 0;
            this.answers = [];
            this.result = null;
            this.is_passed = null;
            this.start = false;
            clearInterval(this.timer_interval);
            this.timer_interval = null;
            this.current_time = null;
        },
    },
});
