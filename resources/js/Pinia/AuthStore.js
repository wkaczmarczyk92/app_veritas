
import { defineStore } from 'pinia';
import { try_catch } from '@/Composables/TryCatch';

export const use_auth_store = defineStore('auth_store', {
    state: () => {
        return {
            user: null
        }
    },
    actions: {
        async get_auth_user() {
            let response = await axios.post(route('auth.user'))
            this.user = response.data
            console.log(this.user)
        },
        username() {
            return this.user.user_profiles ? this.user.user_profiles.first_name + ' ' + this.user.user_profiles.last_name : this.user.email
        }
    }
});