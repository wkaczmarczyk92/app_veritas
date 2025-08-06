import { defineStore } from "pinia";
import { use_processing_store } from "../ProcessingStore";
import { AlertStore } from "../AlertStore";
import { try_catch } from "@/Composables/TryCatch";
import { router } from '@inertiajs/vue3'

export const use_auth_mimic_store = defineStore("auth_mimic_store", {
    state: () => {
        return {
            alert_store: AlertStore(),
            processing_store: use_processing_store(),
        };
    },
    actions: {
        async login_as_user(user_id) {
            await try_catch(async () => {
                let response = await axios.post(route('auth.mimic.login.as.user'), {
                    user_id: user_id
                })

                response = response.data;

                if (response.msg) {
                    this.alert_store.pushAlert(response);
                }

                if (response.success) {
                    router.get(route(response.redirect_to))
                }
            }, this)
        },
        async back_to_admin_panel() {
            await try_catch(async () => {
                let response = await axios.post(route('auth.mimic.back.to.admin.panel'))

                response = response.data;

                if (response.msg) {
                    this.alert_store.pushAlert(response);
                }

                if (response.success) {
                    router.get(route('dashboard'))
                }
            }, this)
        },
    },
});
