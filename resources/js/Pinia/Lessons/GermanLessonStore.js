import { defineStore } from "pinia";
import axios from "axios";
import { AlertStore } from "../AlertStore";
import { use_processing_store } from "../ProcessingStore";
import { router } from "@inertiajs/vue3";
import { try_catch } from "@/Composables/TryCatch";

export const use_german_lesson_store = defineStore("german_lesson_store", {
    state: () => {
        return {
            lesson: null,
            selected_visibility: null,
            alert_store: AlertStore(),
            processing_store: use_processing_store(),
        };
    },
    actions: {
        set_lesson(lesson) {
            this.lesson = lesson;
        },
        set_selected_visibility() {
            this.selected_visibility = this.lesson.visibility_id;
        },
        async update_visibility() {
            try_catch(async () => {
                let response = await axios.patch(
                    route("german.lessons.update.visibility"),
                    {
                        lesson_id: this.lesson.id,
                        visibility_id: this.selected_visibility,
                    }
                );

                console.log(response);

                response = response.data;
                this.alert_store.pushAlert(response);

                if (response.success) {
                    this.lesson.visibility_id = this.selected_visibility;
                    this.lesson.visibility = response.visibility;
                }
            }, this);
        },
        async destroy(id) {
            if (confirm("Na pewno chcesz usunąć wybraną lekcję?")) {
                try_catch(async () => {
                    let response = await axios.delete(
                        route("german.lessons.destroy", {
                            id: id,
                        })
                    );

                    response = response.data;

                    this.alert_store.pushAlert(response);

                    if (response.success) {
                        router.get(route("german.lessons.index"));
                    }
                }, this);
            }
        },
    },
});
