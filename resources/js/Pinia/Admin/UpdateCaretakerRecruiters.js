import { defineStore } from "pinia";
import { use_processing_store } from "../ProcessingStore";
import { AlertStore } from "../AlertStore";
import { try_catch } from "@/Composables/TryCatch";

export const use_update_caretaker_recruiter_store = defineStore("update_caretaker_recruiter_store", {
    state: () => {
        return {
            alert_store: AlertStore(),
            processing_store: use_processing_store(),
        };
    },
    actions: {
        async submit() {
            return try_catch(async () => {
                let response = await axios.post(route('advance.settings.caretakers.update.recruiter'))
                response = response.data

                if (response.success) {
                    this.processing_store.progress_value = 100
                    this.alert_store.pushAlert(response)
                }
            }, this)
        }
    },
});
