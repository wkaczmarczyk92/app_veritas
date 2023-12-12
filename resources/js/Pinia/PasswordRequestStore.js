import { defineStore } from 'pinia';
import axios from 'axios';

export const PasswordRequestStore = defineStore('passwordRequestStore', {
    state: () => {
        return {
            password_request_count: 0
        }
    },
    actions: {
        getPasswordRequestCount() {
            return this.password_request_count;
        },
        async countPasswordRequests() {
            let response = await axios.post(route('password.request.count'));
            this.password_request_count = response.data.password_request_count;
        },
        async update(data) {
            let response = await axios.patch(route('password.request.update'), { 
                user_id: data.user_id,
                password: data.password,
                password_confirmation: data.password_confirmation
            });

            return response.data;
        }
    }
});