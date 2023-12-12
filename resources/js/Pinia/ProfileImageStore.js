import { defineStore } from 'pinia';
import axios from 'axios';

export const ProfileImageStore = defineStore('ProfileImageStore', {
    state: () => {
        return {
            count_users: 0,
            count_unverified: 0
        }
    },
    actions: {
        async getNotVefiried() {
            let response = await axios.post(route('user.profile.image.index'));
            this.count_users = response.data.users.length;
            console.log(this.count_users);

            return response.data.users;
        },
        async massAccept(ids) {
            let response = await axios.patch(route('user.profile.image.mass.accept'), {
                ids: ids
            });

            return response.data;
        },
        async countUnverified() {
            let response = await axios.post(route('count.unverified.profile.img'));
            this.count_unverified = response.data;
        },
        getUnverified() {
            return this.count_unverified;
        }
    }
});