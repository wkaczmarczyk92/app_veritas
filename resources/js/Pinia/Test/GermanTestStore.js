import { defineStore } from "pinia";
import axios from "axios";
import { AlertStore } from "../AlertStore";
import { use_processing_store } from "../ProcessingStore";
import { router } from "@inertiajs/vue3";
import { try_catch } from "@/Composables/TryCatch";

export const use_german_test_store = defineStore("german_test_store", {
    state: () => {
        return {
            german_lesson: null,
            alert_store: AlertStore(),
            processing_store: use_processing_store(),
            errors: null
        };
    },
    actions: {
        set_lesson(german_lesson) {
            this.german_lesson = german_lesson;
        },
        async update_settings() {
            this.errors = null;

            try_catch(async () => {
                let response = await axios.patch(
                    route("german.tests.settings.update"),
                    {
                        test_time: this.german_lesson.test_time,
                        question_count: this.german_lesson.question_count
                    }
                );
                response = response.data;
                this.alert_store.pushAlert(response);

                if (response.errors) {
                    this.errors = response.errors
                }
            }, this);
        },
    },
});
