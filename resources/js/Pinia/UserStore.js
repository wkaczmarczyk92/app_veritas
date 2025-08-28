import { defineStore } from "pinia";
import axios from "axios";
import { try_catch } from "@/Composables/TryCatch";
import { AlertStore } from "./AlertStore";
import { use_processing_store } from "./ProcessingStore";

export const useUserStore = defineStore("userStore", {
    state: () => {
        return {
            user: null,
            alert_store: AlertStore(),
            processing_store: use_processing_store(),
            errors: null,
        };
    },
    actions: {
        new_user_init() {
            this.user = {
                email: "",
                first_name: "",
                last_name: "",
                role_id: "",
                password: "",
                password_confirmation: "",
            };
        },
        async store() {
            try_catch(async () => {
                let response = await axios.post(
                    route("users.store", {
                        ...this.user,
                    })
                );

                response = response.data;

                if (response.msg) {
                    this.alert_store.pushAlert(response);
                }

                if (response.errors) {
                    this.errors = response.errors;
                    console.log("user store errors", this.errors);
                }

                if (response.success) {
                    this.new_user_init();
                }
            }, this);
        },
        async set_user(user = null) {
            if (user != null) {
                this.user = user;
                return;
            }

            if (this.user == null) {
                let response = await axios.get(route("user.get"));
                response = response.data;

                this.user = response;
            }

            return true;
        },
        async set_premium_account(id = null) {
            if (
                !confirm("Na pewno chcesz aktywować konto premium użytkownika?")
            ) {
                return false;
            }

            let response = await try_catch(async () => {
                let user_id = id ?? this.user?.id;

                if (!user_id) {
                    throw new Error("Invalid USER ID");
                }

                let response = await axios.patch(
                    route("user.promote.to.premium", {
                        user_id: user_id,
                    })
                );

                response = response.data;

                if (response.success) {
                    this.user.user_profiles.crt_id_caretaker =
                        response.user.user_profiles.crt_id_caretaker;
                    this.user.user_profiles.recruiter_first_name =
                        response.user.user_profiles.recruiter_first_name;
                    this.user.user_profiles.recruiter_last_name =
                        response.user.user_profiles.recruiter_last_name;
                }

                this.alert_store.pushAlert(response);

                return response.success;
            }, this);

            return response;
        },
        update(key = null) {},
        update_user_profile_image(user_profile_image) {
            this.user.user_profile_image = user_profile_image;
        },
        update_departure_date(ready_to_departure_dates) {
            this.user.ready_to_departure_dates = ready_to_departure_dates;
        },
        async refresh() {
            await this.set_user();
        },
        username() {
            return this?.user?.user_profiles.first_name ?? "-";
        },
        logout() {
            this.user = null;
            // console.log('logout', this.user)
        },
    },
});
