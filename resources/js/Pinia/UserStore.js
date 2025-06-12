import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('userStore', {
    state: () => {
        return {
            user: null
        }
    },
    actions: {
        async set_user(user = null) {
            if (user != null) {
                this.user = user;
                return;
            }

            if (this.user == null) {
                let response = await axios.get(route('user.get'));
                response = response.data;

                this.user = response;
            }
        },
        update(key = null) {

        },
        update_user_profile_image(user_profile_image) {
            this.user.user_profile_image = user_profile_image
        },
        update_departure_date(ready_to_departure_dates) {
            this.user.ready_to_departure_dates = ready_to_departure_dates
        },
        async refresh() {
            await this.set_user();
        },
        username() {
            return this?.user?.user_profiles.first_name ?? '-';
        },
        logout() {
            this.user = null
            // console.log('logout', this.user)
        }
    }
});
